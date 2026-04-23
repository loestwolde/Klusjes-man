CREATE DATABASE klusbedrijf;
USE klusbedrijf;

-- Tabel: klanten
CREATE TABLE klanten (
    klant_id INT AUTO_INCREMENT PRIMARY KEY,
    aanhef VARCHAR(10),
    voorletters VARCHAR(10),
    achternaam VARCHAR(255),
    email VARCHAR(255),
    telefoon VARCHAR(20)
);

-- Tabel: adressen
CREATE TABLE adressen (
    adres_id INT AUTO_INCREMENT PRIMARY KEY,
    straat VARCHAR(255),
    huisnummer VARCHAR(5),
    huisnummer_toevoeging VARCHAR(5),
    postcode VARCHAR(10),
    woonplaats VARCHAR(100)
);

-- Koppeltabel: klant_adres (many-to-many + periode)
CREATE TABLE klant_adres (
    klant_id INT,
    adres_id INT,
    startdatum DATE,
    einddatum DATE,
    PRIMARY KEY (klant_id, adres_id, startdatum),
    FOREIGN KEY (klant_id) REFERENCES klanten(klant_id),
    FOREIGN KEY (adres_id) REFERENCES adressen(adres_id)
);

-- Tabel: factuur
CREATE TABLE factuur (
    factuur_id INT AUTO_INCREMENT PRIMARY KEY,
    klant_id INT,
    factuurdatum DATE,
    totaalbedrag DECIMAL(10,2),
    betaald DATE,
    FOREIGN KEY (klant_id) REFERENCES klanten(klant_id)
);

-- Tabel: factuurregels
CREATE TABLE factuurregels (
    factuurregel_id INT AUTO_INCREMENT PRIMARY KEY,
    factuur_id INT,
    omschrijving VARCHAR(255),
    aantal INT,
    prijs_per_stuk DECIMAL(10,2),
    totaalprijs DECIMAL(10,2),
    FOREIGN KEY (factuur_id) REFERENCES factuur(factuur_id)
);

-- Tabel: werkzaamheden
CREATE TABLE werkzaamheden (
    werkzaamheid_id INT AUTO_INCREMENT PRIMARY KEY,
    omschrijving VARCHAR(255),
    prijs_per_stuk DECIMAL(10,2)
);

-- Tabel: artikelen
CREATE TABLE artikelen (
    artikel_id INT AUTO_INCREMENT PRIMARY KEY,
    naam VARCHAR(255),
    prijs DECIMAL(10,2),
    voorraad INT
);