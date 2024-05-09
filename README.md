## Seznámení s Laravelem

Zadáním bylo vytvořit a rozchodit jednoduchý Laravel framework s webovým serverem, vytvoření jednoduchých endpointů pro obsluhu GET a POST requestů a zprovoznění automatického testování.

### Rozchození projektu

Nejdříve jsem využil příkaz:

```composer create-project --prefer-dist laravel/laravel```

který vytvořil Laravel projekt pomocí composeru - dependency manageru php. V tomto základním projektu již byly všechny věci potřebné pro spuštění webového serveru.

Na spuštění tohoto serveru jsem využil příkaz:

```php artisan serve```

Server se spustí lokálně na 127.0.0.1:8000. Pro přístup k později vytvořeným endpointům stačí za tuto adresu dopsat "/" a název endpointu.

### Endpointy

Pro endpointy je nejdříve nutné specifikovat "route" v souboru ```routes/web.php```. Ke jménu endpointu je zde přiřazena metoda, která endpoint obsluhuje.

Pro definici chování endpointu je poté nutné metody uvedené v routes implementovat v souboru ```app/http/controllers/Controller.php```. Zde jsem pro každý endpoint implementoval jednu metodu.

Pro obsluhu prvního endpointu typu GET (pojmenovaný ```first-endpoint```) jsem implementoval jednoduchou metodu, která vrátí JSON se zprávou, že uživatel přistoupil na endpoint.

Pro obsluhu druhého endpointu typu POST (ten se jmenuje ```post-endpoint```) jsem implementoval metodu, která uloží zaslaná data do proměnné. 

Protože endpoint očekává jméno, heslo a email, implementoval jsem také kontrolu validity emailu v ```app/Models/User.php``` a využil fillable vlastnosti atributů třídy User, která je v tomto souboru popsána.

### Testování

Pro testování jsem vytvořil soubor ```tests/Feature/httpTests.php```. 

V tomto souboru je celkem 5 testů. První z testů je základní test ověřující funkčnost celé aplikace, který je automaticky vytvořen spolu s projektem.

Další 4 testy jsou poté mnou navržené, ověřující různé případy použití dvou implementovaných endpointů.

Pro endpoint GET je testován pouze kód odezvy 200, protože v endpointu není žádná logika, která by vyžadovala složitější otestování.

Pro POST endpoint jsou implementovány 3 testy, jeden test zachycuje případ, kdy jsou všechny informace vyžadované endpointem v pořádku.

Další dva testy poté zkoušejí robustnost endpointu tím, že při nich není poskytnut email, který je povinný, nebo je email ve špatném tvaru.

Testy lze spustit při výběru konfigurace httpTests a tlačítka run.

V testech nebyla použita statická cesta (127.0.0.1:8000), ale raději jsem využil proměnné dostupné v konfiguraci ```config('http-client.host')```. Díky tomu budou testy použitelné i při nasazení na nějaké jiné webové adrese.

## Úkol trvání

Dalším úkolem bylo navrhnout systém počítající odhad dokončení úkolu. Měly přitom být (volitelně) zahrnuty víkendy i statní svátky.

Pro tento úkol jsem vytvořil dva další endpointy, ```/work-day``` a ```/trvani```.

### Databáze

Pro určení toho, zda je den státní svátek nebo ne, jsem využil databázi. Tu jsem si nejdříve musel lokálně zprovoznit (mysql percona) a následně v ní vytvořit tabulku ```Holidays```.

Jedná se o primitivní tabulku sestávající ze dvou sloupců, a to dne a státu, ve kterém je tento svátek slaven. Díky tomu je jednoduché udělat případné rozšíření, kdy se snažíme odhadovat čas pro vypracování například amerického kolegy.

Do této tabulky jsem ručně vložil státní svátky slavené v Česku v roce 2024. Zautomatizování tohoto procesu nebylo úkolem zadáno, ale nebylo by příliš složité, případně by stačilo vytvořit jednoduchý skript a státní svátky pro následující roky vypočítat.

### Service

Z této tabulky následně čerpá service ```WorkDayService```, která je umístěna v ```app/Services```. 

Skládá se ze dvou metod. První z nich je metoda ```isWorkDay($date)```, která pro zadané vstupní datum určí, zda je den pracovním dnem, nebo není.

Kromě mnou vytvořené tabulky je k tomu použita vestavěná metoda ```isWeekend``` knihovny Carbon.

Druhou metodou je metoda ```calculateCompletionDate``` (implementovaná ve stejném souboru), jejíž vstupní argumenty splňují zadání (tedy počáteční datum, předpokládaná délka trvání, zda zohledňovat pracovní dny, začátek a konec pracovní doby).

### Testování

Pro testování jsem vytvořil tři metody. Jedna testuje trvání zahrnující státní svátek i víkend, druhá metoda pouze státní svátek, a třetí metoda nezohledňující pracovní a nepracovní dny.

Testy jsou implementovány v souboru ```tests/Feature/workdayTest.php```.

Protože se z mého pohledu jednalo o druhou feature, implementoval jsem tyto testy do jiného souboru, než testy pro předchozí úkol. Je proto jednodušší testovat tuto feature zvlášť.
