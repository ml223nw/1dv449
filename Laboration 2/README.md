# Mathias Lundberg (ml223nw) - Laboration 2 - 1dv449
# Säkerhetsproblem

## Parametriserade frågor

Variablerna “usename” och “password” i login.js konkateneras direkt in i SQL-frågan. Eftersom detta inte är separerat från användares inmatning så är applikationen öppen för SQL-injections. Det innebär att användaren kan manipulera SQL-frågor som körs i applikationen[1].

Exempelvis så kunde man logga in i applikationen genom att bara lägga till 1=1 i användarnamnet. Det går också att använda ”;DROP TABLE tabellnamn” för att radera tabeller i applikationen. Det kommer inte att vara så svårt eller ta specielt lång tid att räkna ut vad tabellnamnet är (message) för att kunna påverka applikationen på ett förödande sätt[1].

## Autentisering

Vid utloggning så förstörs inte sessionen. Angriparen kan genom att navigera sig manuellt i adressfältet få fulla rättigheter till användarkonto[5]. 
Eftersom sessionen inte förstörs när man loggar ut, så är det bara bara att ange “/message” direkt i url:n då sessionen autentiserar användaren igen.

## XSS

En XSS brist är när en applikation accepterar data utan att någon ordentlig validering. Det innebär att angriparen kan mata in HTML eller JavaScript skript i formuläret som gör att användaren omdirigeras till en sida med skadlig programvara[1].

I applikationen går det exempelvis att skriva
<code><a href='#' onclick='alert(document.cookie)'>Click me</a></code> som ett meddelande och när man sen klickar på länken så visas användarens sessioncookie upp i en varningsruta. På så sätt så kan angriparen kapa användaren konto[2].

## Hashade lösenord

Lösenord är sparade i klartext. Det finns inte någon hashad eller krypterade lösenord i applikationen. Detta medför att lösenord syns som klartext i databasen.
Angriparen kan med hjälp av att skicka SQL-frågor komma åt känslig data från databasen som lösenord eller kreditkortsnummer[1].

Känslig data bör hashas innan det sparas i databasen. Det finns olika metoder för detta, till exempel sha1 och md5. Tyvärr är dessa alternativ kanske inte så bra då det går att knäcka med en “brute force”.

Istället bör man använda sig av en hashmetod som heter BCrypt istället, som i nuläget anses vara det bästa alternativet för att säkra känslig data.

## HTTPS

Med hjälp av HTTPS så kan man kryptera trafiken mellan klienten och server. Detta görs inte i denna applikation. Angriparen kan bevaka trafiken och på så sätt komma åt cookies från användare[5].

Lösningen på detta är att använda sig av en säkerhetsmekanism som heter SSL. Detta krypterar kommunikationen mellan två enheter så att ingen annan i nätverket ska kunna avlyssna eller manipulera informationen[7].

## HttpOnly är satt till “false”

Detta används för att skydda cookies. Men eftersom värdet är satt till “false” så finns inget skydd för detta i applikationen.
Detta är ett problem relaterat till XSS därför att om HttpOnly skulle vara satt till “true” så kan det drastiskt minska XSS attack[3]. Detta på grund av att cookies inte kommer att kunna kommas åt av JavaScript på klienten.

Det går att ändra värdet på HttpOnly till “true” i express.js.

## JSON-data är tillgängigt

Är man på inloggningssidan och öppnar konsollen i webbläsaren (Chrome i detta fall) därefter går in på fliken “Network” och öppnar filen “data” så får man upp alla meddelanden i applikationen. Detta gäller alltså oavsett om man är inloggad eller inte.
Detta är en stor säkerhetsrisk[3], då det ligger i klartext och vem som helst kan läsa detta[5]. 
Det bör alltså ske en autentisering som faktiskt ser över att användaren verkligen är inloggad innan ett svar skickas till klienten.

# Prestandaproblem

## JavaScript filer felaktigt placerade

Dessa filer ska länkas så långt ner som möjligt i HTML dokumentet, vilket det inte gör i denna applikation. Eftersom dokumentet läses in uppifrån och ner så sker nedladdningen i den ordningen också. Att flytta länkarna så långt ner som möjligt möjliggör till bättre prestanda[6].

JavaScript filer tar oftast längre tid att ladda ner än övriga resurser. 
Placeras JavaScript filer långt upp i dokumentet så kommer andra resurser att “pausa” medans det läses in.
Inte nog med att det att det tar längre tid att ladda, sidan kommer inte att renderas föränn JavaScript filerna har lästs in.


## Minifiera JavaScript och CSS

Att minifiera skripten innebär att man låter koden gå igenom en process som helt enkelt ska förminska koden. Variabler ändras till mindre antal tecken, onödiga tecken och tomrum raderas, samt att koden blir mindre läsbar.
Reslutatet av detta blir bättre prestanda i form av snabbare laddningstider och mindre storlek på filerna[6].

Vad man dock ska tänka på är att om man minifierar skript så kan man också få mer problem i applikationen än man hade tidigare[5]. Detta på grund av att under minifieringen så ändras variablers namn bland annat. Man kan inte lita på att minifieringen har lyckats fått med alla ändringar i referenserna som krävs för att applikationen ska fungera korrekt.

## Minska HTTP förfrågningar

Det går att reducera antalet HTTP förfrågningar genom att fastställa hur länge applikationen ska behålla informationen i en cache fil. På så sätt kan man öka prestandan [1]. 

Expiration headern är inställd på ”'-1”. Detta betyder att ingenting sparas i cache fil, utan måste hämtas om på nytt varje gång sidan läses in.


# Reflektion

(kommer snart)

# Referenser

[1] OWASP, "OWASP Top 10 - 2013 - The ten most critical web application security risks", OWASP, september 2015 [Online] Tillgänglig: https://www.owasp.org/index.php/Top10#OWASP_Top_10_for_2013. [Hämtad: 26 november, 2015].

[2] M. Coates, "Application Security - Understanding, Exploiting and Defending against Top Web Vulnerabilities" CernerEng, Mars 2014 [Online], 15 min 0 sek, Tillgänglig: http://www.youtube.com/watch?v=sY7pUJU8a7U&t=15m0s [Hämtad: 24 november, 2015].

[3] "XSS (Cross Site Scripting) Prevention Cheat Sheet" Open Web Application Security Project, September 2015 [Online] Tillgänglig: https://www.owasp.org/index.php/XSS_(Cross_Site_Scripting)_Prevention_Cheat_Sheet [Hämtad: 28 november, 2015]

[6] Steve Souders, "High Performance Websites: Essential knowledge for frontend engineers", O'Reilly, 2007

[5] John Häggerud, "Laboration 02" Linnéuniversitetet, November 2015 [Online] Tillgänglig: https://coursepress.lnu.se/kurs/webbteknik-ii/laborationer/laboration-02/ [Hämtad: 25 november, 2015]

[6] "Authentication Cheat Sheet" Open Web Application Security Project, November 2015. [Online] Tillgänglig: https://www.owasp.org/index.php/Authentication_Cheat_Sheet  [Hämtad: 2 december 2015]

[7] Wikipedia, “Secure Sockets Layer”, 4 Februari 2015. [Online] Tillgänglig: https://sv.wikipedia.org/wiki/Secure_Sockets_Layer [Hämtad: 2 decemder 2015]
