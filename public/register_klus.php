<?php
// ============================================================
//  Configuratie
// ============================================================
define('DB_HOST', 'localhost');
define('DB_NAME', 'klusbedrijf');
define('DB_USER', 'root');   // <-- aanpassen
define('DB_PASS', '');       // <-- aanpassen

// Standaard uurtarief (wordt automatisch ingevuld)
define('STANDAARD_UURTARIEF', 50.00);

// Werkzaamheden met eigen standaard tarief (pas aan naar wens)
// Als een werkzaamheid geen eigen tarief heeft, wordt STANDAARD_UURTARIEF gebruikt.
$WERKZAAMHEDEN = [
    1 => ['naam' => 'Schilderwerk',     'prijs' => 50.00],
    2 => ['naam' => 'Houtzagen',        'prijs' => 45.00],
    3 => ['naam' => 'Bakstenen leggen', 'prijs' => 55.00],
    4 => ['naam' => 'Straatwerk',       'prijs' => 48.00],
    5 => ['naam' => 'Overig',           'prijs' => STANDAARD_UURTARIEF],
];

// ============================================================
//  Database helpers
// ============================================================
function db(): PDO
{
    static $pdo;
    if (!$pdo) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
    return $pdo;
}

function h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function go(string $url): void
{
    header('Location: ' . $url);
    exit;
}

// Tabel aanmaken (geen FK naar aparte werkzaamheden-tabel)
db()->exec("CREATE TABLE IF NOT EXISTS klussen (
    klus_id          INT AUTO_INCREMENT PRIMARY KEY,
    werkzaamheid_id  INT DEFAULT NULL,
    omschrijving     VARCHAR(255) NOT NULL,
    klant            VARCHAR(150) NOT NULL,
    datum            DATE NOT NULL,
    aantal           DECIMAL(8,2) NOT NULL DEFAULT 1,
    prijs_per_stuk   DECIMAL(10,2) NOT NULL DEFAULT 0,
    notities         TEXT DEFAULT NULL,
    aangemaakt_op    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// ============================================================
//  Routing
// ============================================================
$actie = $_GET['actie'] ?? 'lijst';
$id    = (int)($_GET['id'] ?? 0);
$fout  = '';
$item  = null;

// --- OPSLAAN ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $actie === 'opslaan') {
    $wid    = $_POST['werkzaamheid_id'] ? (int)$_POST['werkzaamheid_id'] : null;
    $omschr = trim($_POST['omschrijving'] ?? '');
    $klant  = trim($_POST['klant'] ?? '');
    $datum  = $_POST['datum'] ?? '';
    $aantal = (float) str_replace(',', '.', $_POST['aantal'] ?? '1');
    $prijs  = (float) str_replace(',', '.', $_POST['prijs_per_stuk'] ?? '0');
    $noot   = trim($_POST['notities'] ?? '');
    $editId = (int)($_POST['klus_id'] ?? 0);

    if (!$omschr || !$klant || !$datum) {
        $fout  = 'Omschrijving, klant en datum zijn verplicht.';
        $actie = $editId ? 'bewerken' : 'nieuw';
        $id    = $editId;
    } else {
        if ($editId) {
            db()->prepare("UPDATE klussen SET werkzaamheid_id=?,omschrijving=?,klant=?,datum=?,
                           aantal=?,prijs_per_stuk=?,notities=? WHERE klus_id=?")
               ->execute([$wid, $omschr, $klant, $datum, $aantal, $prijs, $noot, $editId]);
        } else {
            db()->prepare("INSERT INTO klussen
                           (werkzaamheid_id,omschrijving,klant,datum,aantal,prijs_per_stuk,notities)
                           VALUES (?,?,?,?,?,?,?)")
               ->execute([$wid, $omschr, $klant, $datum, $aantal, $prijs, $noot]);
        }
        go('?actie=lijst&ok=1');
    }
}

// --- VERWIJDEREN ---
if ($actie === 'verwijderen' && $id) {
    db()->prepare("DELETE FROM klussen WHERE klus_id=?")->execute([$id]);
    go('?actie=lijst&verwijderd=1');
}

// --- BEWERKEN: data laden ---
if ($actie === 'bewerken' && $id) {
    $item = db()->prepare("SELECT * FROM klussen WHERE klus_id=?");
    $item->execute([$id]);
    $item = $item->fetch();
    if (!$item) go('?actie=lijst');
}

// --- LIJST ---
$rijen       = [];
$zoek        = trim($_GET['zoek'] ?? '');
$filterWerk  = (int)($_GET['werkzaamheid_id'] ?? 0);

if ($actie === 'lijst') {
    $where  = [];
    $params = [];

    if ($zoek) {
        $where[]  = "(omschrijving LIKE ? OR klant LIKE ?)";
        $params[] = '%' . $zoek . '%';
        $params[] = '%' . $zoek . '%';
    }
    if ($filterWerk) {
        $where[]  = "werkzaamheid_id = ?";
        $params[] = $filterWerk;
    }

    $sql  = "SELECT * FROM klussen";
    if ($where) $sql .= " WHERE " . implode(' AND ', $where);
    $sql .= " ORDER BY datum DESC";

    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    $rijen = $stmt->fetchAll();
}

// Tarieven + namen als JSON voor JS auto-fill
$js_tarieven = [];
$js_namen    = [];
foreach ($WERKZAAMHEDEN as $wid => $w) {
    $js_tarieven[$wid] = $w['prijs'];
    $js_namen[$wid]    = $w['naam'];
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Klus Registratie</title>

</head>

<body>
<header>
    <h1>Klus Registratie</h1>
    <nav>
        <a href="index.php" class="btn btn-ghost">← Klanten</a>
        <br>
        <?php if ($actie !== 'lijst'): ?>
            <a href="?actie=lijst" class="btn btn-ghost">Overzicht</a>
        <?php endif; ?>
        <a href="?actie=nieuw" class="btn btn-primary">+ Nieuwe klus</a>
    </nav>
</header>

<main>

<?php /* ===== LIJST ===== */ if ($actie === 'lijst'): ?>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-ok">✓ Klus opgeslagen.</div>
    <?php endif; ?>
    <?php if (isset($_GET['verwijderd'])): ?>
        <div class="alert alert-ok">Klus verwijderd.</div>
    <?php endif; ?>

    <?php $omzet = array_sum(array_map(fn($r) => $r['aantal'] * $r['prijs_per_stuk'], $rijen)); ?>
    <div class="stats">
        <div class="stat">
            <div class="n"><?= count($rijen) ?></div>
            <div class="l">Klusjes</div>
        </div>
        <div class="stat">
            <div class="n">€&nbsp;<?= number_format($omzet, 2, ',', '.') ?></div>
            <div class="l">Totale omzet</div>
        </div>
    </div>

    <div class="toolbar">
        <form method="get">
            <input type="hidden" name="actie" value="lijst">

            <!-- Filter op werkzaamheid -->
            <select name="werkzaamheid_id" onchange="this.form.submit()">
                <option value="">— Alle werkzaamheden —</option>
                <?php foreach ($WERKZAAMHEDEN as $wid => $w): ?>
                    <option value="<?= $wid ?>" <?= $filterWerk === $wid ? 'selected' : '' ?>>
                        <?= h($w['naam']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Vrij zoeken -->
            <input type="text" name="zoek" placeholder="Zoek op klant of omschrijving…"
                   value="<?= h($zoek) ?>">
            <button class="btn btn-ghost" type="submit">Zoeken</button>
            <?php if ($zoek || $filterWerk): ?>
                <a href="?actie=lijst" class="btn btn-ghost">✕ Wis</a>
            <?php endif; ?>
        </form>
        <a href="?actie=nieuw" class="btn btn-primary" style="margin-left:auto">+ Nieuwe klus</a>
    </div>

    <div class="card" style="padding:0">
        <div class="twrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Werkzaamheid</th>
                        <th>Omschrijving</th>
                        <th>Klant</th>
                        <th>Datum</th>
                        <th style="text-align:right">Uren</th>
                        <th style="text-align:right">Tarief p/u</th>
                        <th style="text-align:right">Totaal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$rijen): ?>
                        <tr>
                            <td colspan="9" style="text-align:center;color:var(--muted);padding:2rem">
                                Geen klusjes gevonden.
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($rijen as $r): ?>
                        <?php $werkNaam = $WERKZAAMHEDEN[$r['werkzaamheid_id']]['naam'] ?? '—'; ?>
                        <tr>
                            <td style="color:var(--muted)"><?= $r['klus_id'] ?></td>
                            <td><span class="badge"><?= h($werkNaam) ?></span></td>
                            <td><?= h($r['omschrijving']) ?></td>
                            <td><?= h($r['klant']) ?></td>
                            <td><?= date('d-m-Y', strtotime($r['datum'])) ?></td>
                            <td style="text-align:right"><?= number_format($r['aantal'], 2, ',', '') ?></td>
                            <td style="text-align:right">€&nbsp;<?= number_format($r['prijs_per_stuk'], 2, ',', '.') ?></td>
                            <td style="text-align:right;font-weight:600">
                                €&nbsp;<?= number_format($r['aantal'] * $r['prijs_per_stuk'], 2, ',', '.') ?>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="?actie=bewerken&id=<?= $r['klus_id'] ?>"
                                       class="btn btn-ghost btn-sm">Bewerken</a>
                                    <a href="?actie=verwijderen&id=<?= $r['klus_id'] ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Klus verwijderen?')">Verwijderen</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php /* ===== FORMULIER ===== */ elseif (in_array($actie, ['nieuw', 'bewerken'])): ?>

    <?php $f = $item ?? []; ?>

    <div class="card">
        <div class="card-title">
            <?= $item ? 'Klus #' . $item['klus_id'] . ' bewerken' : 'Nieuwe klus registreren' ?>
        </div>

        <?php if ($fout): ?>
            <div class="alert alert-err"><?= h($fout) ?></div>
        <?php endif; ?>

        <form method="post" action="?actie=opslaan" id="form">
            <?php if ($item): ?>
                <input type="hidden" name="klus_id" value="<?= $item['klus_id'] ?>">
            <?php endif; ?>

            <!-- Werkzaamheid selecteren -->
            <div style="margin-bottom:1rem">
                <label>Werkzaamheid</label>
                <select name="werkzaamheid_id" id="werkSelect" onchange="werkKiezen(this)">
                    <option value="">— Kies een werkzaamheid —</option>
                    <?php foreach ($WERKZAAMHEDEN as $wid => $w): ?>
                        <option value="<?= $wid ?>"
                            data-prijs="<?= $w['prijs'] ?>"
                            <?= ($f['werkzaamheid_id'] ?? null) == $wid ? 'selected' : '' ?>>
                            <?= h($w['naam']) ?> — € <?= number_format($w['prijs'], 2, ',', '.') ?>/u
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="fg">
                <div class="full">
                    <label>Omschrijving *</label>
                    <input type="text" name="omschrijving" id="omschrijving"
                           value="<?= h($f['omschrijving'] ?? $_POST['omschrijving'] ?? '') ?>"
                           placeholder="Wordt automatisch ingevuld vanuit werkzaamheid, of typ zelf"
                           required>
                </div>
                <div>
                    <label>Klant *</label>
                    <input type="text" name="klant"
                           value="<?= h($f['klant'] ?? $_POST['klant'] ?? '') ?>"
                           placeholder="Naam klant" required>
                </div>
                <div>
                    <label>Datum *</label>
                    <input type="date" name="datum"
                           value="<?= h($f['datum'] ?? $_POST['datum'] ?? date('Y-m-d')) ?>"
                           required>
                </div>
            </div>

            <div class="fg3" style="margin-top:1rem">
                <div>
                    <label>Aantal uren</label>
                    <input type="number" name="aantal" id="aantal" step="0.5" min="0"
                           value="<?= h($f['aantal'] ?? $_POST['aantal'] ?? '1') ?>"
                           oninput="herbereken()">
                </div>
                <div>
                    <label>Uurtarief (€)</label>
                    <input type="number" name="prijs_per_stuk" id="prijs" step="0.01" min="0"
                           value="<?= h($f['prijs_per_stuk'] ?? $_POST['prijs_per_stuk'] ?? number_format(STANDAARD_UURTARIEF, 2, '.', '')) ?>"
                           oninput="herbereken()">
                </div>
                <div>
                    <label>Totaal (€)</label>
                    <input type="text" id="totaal" readonly
                           style="background:var(--bg);font-weight:600;color:var(--primary)"
                           value="0,00">
                </div>
            </div>

            <div style="margin-top:1rem">
                <label>Notities</label>
                <textarea name="notities"><?= h($f['notities'] ?? $_POST['notities'] ?? '') ?></textarea>
            </div>

            <div class="factions">
                <button type="submit" class="btn btn-primary">Opslaan</button>
                <a href="?actie=lijst" class="btn btn-ghost">Annuleren</a>
            </div>
        </form>
    </div>

    <script>
        const tarieven = <?= json_encode($js_tarieven) ?>;
        const namen    = <?= json_encode($js_namen) ?>;
        const standaardTarief = <?= STANDAARD_UURTARIEF ?>;

        function werkKiezen(sel) {
            const wid = sel.value;
            const prijs = wid && tarieven[wid] !== undefined ? tarieven[wid] : standaardTarief;
            document.getElementById('prijs').value = prijs.toFixed(2);

            // Vul omschrijving alleen in als gebruiker die nog niet zelf heeft getypt
            if (wid && !document.getElementById('omschrijving').dataset.aangepast) {
                document.getElementById('omschrijving').value = namen[wid] || '';
            }
            herbereken();
        }

        document.getElementById('omschrijving').addEventListener('input', function () {
            this.dataset.aangepast = '1';
        });

        function herbereken() {
            const a = parseFloat(document.getElementById('aantal').value) || 0;
            const p = parseFloat(document.getElementById('prijs').value) || 0;
            document.getElementById('totaal').value = (a * p).toLocaleString('nl-NL', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        herbereken();
    </script>

<?php endif; ?>
</main>
</body>
</html>