# Vargus kunstimuuseumis – VR

Koostöömäng gümnaasiumi kunstiõpetusse. Üks mängija on VR-prillides (Pico 4 Ultra) tühjaks varastatud muuseumisaalis, meeskond on telefonide või arvutite taga kriisikeskuses. Prillidega mängija kirjeldab maale, meeskond otsustab kataloogi põhjal, kuhu need kuuluvad, ja koos taastatakse näitused.

Autorid: Aly Valvas (idee, lauamäng 2021–2023), Zeinäb Mirzojeva (kujundus), Agnes Leemets, Riin Abel (küsimused). Veebi- ja VR-versioon: Aly Valvas koos Claude'iga.

## Praegune seis: tehniline katse

- `index.html` – mängutoa kood ja rolli valik
- `muuseum.html` – VR-saal (WebXR, three.js). Töötab ka tavalises brauseris: lohista vaatamiseks, klõpsa tegutsemiseks
- `keskus.html` – kriisikeskus (telefon või arvuti)
- `api.php` – mängutoa server (PHP 7.4+, andmebaasi pole vaja). Seis on failides `data/room_KOOD.json`
- `lib/` – three.js 0.160 ja VRButton (internetti ei vaja)
- `img/` – vabakasutuses maalid

## Paigaldamine cPanelisse (Git Version Control)

1. cPanel » Files » Git Version Control » **Create**
2. Lülita sisse **Clone a Repository**
3. Clone URL: `https://github.com/ALy29/vargus_vr.git`
4. Repository Path: `public_html/vargus`
5. **Create**
6. Kontrolli, et kaustal `data` on kirjutamisõigus (755 või 775)

Mäng on aadressil `https://SINUKOOL.ee/vargus/`. VR töötab ainult HTTPS-iga.

## Uuendamine

cPanel » Git Version Control » repo juures **Manage** » **Pull or Deploy** » **Update from Remote**.

## Katse käik

1. Arvutis või telefonis ava mängu aadress, vali kood ja **Kriisikeskus**.
2. Pico brauseris ava sama aadress, sisesta sama kood, vali **Muuseumisaal** ja vajuta **ENTER VR**.
3. Prillides: suuna kiir maalile ja vajuta päästikut (võta kätte), raamile (riputa), põrandale (liigu). Hoidla on selja taga.
4. Kui kõik kolm raami on õigesti täidetud, süttivad tuled ja ilmub „NÄITUS ON AVATUD“.

## Pildid

Hagen-Schwarz „Naise portree“, Monet „Impressioon. Tõusev päike“ (1872), Caillebotte „Puuviljad turuletil“ (u 1881–1882), van Gogh „Päevalilled“ (1888). Kõik on avalikus omandis.
