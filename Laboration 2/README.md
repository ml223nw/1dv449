# Mathias Lundberg (ml223nw) - Laboration 2 - 1dv449
# S�kerhetsproblem

## Parametriserade fr�gor

Variablerna �usename� och �password� i login.js konkateneras direkt in i SQL-fr�gan. Eftersom detta inte �r separerat fr�n anv�ndares inmatning s� �r applikationen �ppen f�r SQL-injections. Det inneb�r att anv�ndaren kan manipulera SQL-fr�gor som k�rs i applikationen[1].

Exempelvis s� kunde man logga in i applikationen genom att bara l�gga till 1=1 i anv�ndarnamnet. Det g�r ocks� att anv�nda �;DROP TABLE tabellnamn� f�r att radera tabeller i applikationen. Det kommer inte att vara s� sv�rt eller ta specielt l�ng tid att r�kna ut vad tabellnamnet �r (message) f�r att kunna p�verka applikationen p� ett f�r�dande s�tt[1].

## Autentisering

Vid utloggning s� f�rst�rs inte sessionen. Angriparen kan genom att navigera sig manuellt i adressf�ltet f� fulla r�ttigheter till anv�ndarkonto[5]. 
Eftersom sessionen inte f�rst�rs n�r man loggar ut, s� �r det bara bara att ange �/message� direkt i url:n d� sessionen autentiserar anv�ndaren igen.

## XSS

En XSS brist �r n�r en applikation accepterar data utan att n�gon ordentlig validering. Det inneb�r att angriparen kan mata in HTML eller JavaScript skript i formul�ret som g�r att anv�ndaren omdirigeras till en sida med skadlig programvara[1].

I applikationen g�r det exempelvis att skriva
<code><a href='#' onclick='alert(document.cookie)'>Click me</a></code> som ett meddelande och n�r man sen klickar p� l�nken s� visas anv�ndarens sessioncookie upp i en varningsruta. P� s� s�tt s� kan angriparen kapa anv�ndaren konto[2].

## Hashade l�senord

L�senord �r sparade i klartext. Det finns inte n�gon hashad eller krypterade l�senord i applikationen. Detta medf�r att l�senord syns som klartext i databasen.
Angriparen kan med hj�lp av att skicka SQL-fr�gor komma �t k�nslig data fr�n databasen som l�senord eller kreditkortsnummer[1].

K�nslig data b�r hashas innan det sparas i databasen. Det finns olika metoder f�r detta, till exempel sha1 och md5. Tyv�rr �r dessa alternativ kanske inte s� bra d� det g�r att kn�cka med en �brute force�.

Ist�llet b�r man anv�nda sig av en hashmetod som heter BCrypt ist�llet, som i nul�get anses vara det b�sta alternativet f�r att s�kra k�nslig data.

## HTTPS

Med hj�lp av HTTPS s� kan man kryptera trafiken mellan klienten och server. Detta g�rs inte i denna applikation. Angriparen kan bevaka trafiken och p� s� s�tt komma �t cookies fr�n anv�ndare[5].

L�sningen p� detta �r att anv�nda sig av en s�kerhetsmekanism som heter SSL. Detta krypterar kommunikationen mellan tv� enheter s� att ingen annan i n�tverket ska kunna avlyssna eller manipulera informationen[7].

## HttpOnly �r satt till �false�

Detta anv�nds f�r att skydda cookies. Men eftersom v�rdet �r satt till �false� s� finns inget skydd f�r detta i applikationen.
Detta �r ett problem relaterat till XSS d�rf�r att om HttpOnly skulle vara satt till �true� s� kan det drastiskt minska XSS attack[3]. Detta p� grund av att cookies inte kommer att kunna kommas �t av JavaScript p� klienten.

Det g�r att �ndra v�rdet p� HttpOnly till �true� i express.js.

## JSON-data �r tillg�ngigt

�r man p� inloggningssidan och �ppnar konsollen i webbl�saren (Chrome i detta fall) d�refter g�r in p� fliken �Network� och �ppnar filen �data� s� f�r man upp alla meddelanden i applikationen. Detta g�ller allts� oavsett om man �r inloggad eller inte.
Detta �r en stor s�kerhetsrisk[3], d� det ligger i klartext och vem som helst kan l�sa detta[5]. 
Det b�r allts� ske en autentisering som faktiskt ser �ver att anv�ndaren verkligen �r inloggad innan ett svar skickas till klienten.

# Prestandaproblem

## JavaScript filer felaktigt placerade

Dessa filer ska l�nkas s� l�ngt ner som m�jligt i HTML dokumentet, vilket det inte g�r i denna applikation. Eftersom dokumentet l�ses in uppifr�n och ner s� sker nedladdningen i den ordningen ocks�. Att flytta l�nkarna s� l�ngt ner som m�jligt m�jligg�r till b�ttre prestanda[6].

JavaScript filer tar oftast l�ngre tid att ladda ner �n �vriga resurser. 
Placeras JavaScript filer l�ngt upp i dokumentet s� kommer andra resurser att �pausa� medans det l�ses in.
Inte nog med att det att det tar l�ngre tid att ladda, sidan kommer inte att renderas f�r�nn JavaScript filerna har l�sts in.


## Minifiera JavaScript och CSS

Att minifiera skripten inneb�r att man l�ter koden g� igenom en process som helt enkelt ska f�rminska koden. Variabler �ndras till mindre antal tecken, on�diga tecken och tomrum raderas, samt att koden blir mindre l�sbar.
Reslutatet av detta blir b�ttre prestanda i form av snabbare laddningstider och mindre storlek p� filerna[6].

Vad man dock ska t�nka p� �r att om man minifierar skript s� kan man ocks� f� mer problem i applikationen �n man hade tidigare[5]. Detta p� grund av att under minifieringen s� �ndras variablers namn bland annat. Man kan inte lita p� att minifieringen har lyckats f�tt med alla �ndringar i referenserna som kr�vs f�r att applikationen ska fungera korrekt.

## Minska HTTP f�rfr�gningar

Det g�r att reducera antalet HTTP f�rfr�gningar genom att fastst�lla hur l�nge applikationen ska beh�lla informationen i en cache fil. P� s� s�tt kan man �ka prestandan [1]. 

Expiration headern �r inst�lld p� �'-1�. Detta betyder att ingenting sparas i cache fil, utan m�ste h�mtas om p� nytt varje g�ng sidan l�ses in.


# Reflektion

(kommer snart)

# Referenser

[1] OWASP, "OWASP Top 10 - 2013 - The ten most critical web application security risks", OWASP, september 2015 [Online] Tillg�nglig: https://www.owasp.org/index.php/Top10#OWASP_Top_10_for_2013. [H�mtad: 26 november, 2015].

[2] M. Coates, "Application Security - Understanding, Exploiting and Defending against Top Web Vulnerabilities" CernerEng, Mars 2014 [Online], 15 min 0 sek, Tillg�nglig: http://www.youtube.com/watch?v=sY7pUJU8a7U&t=15m0s [H�mtad: 24 november, 2015].

[3] "XSS (Cross Site Scripting) Prevention Cheat Sheet" Open Web Application Security Project, September 2015 [Online] Tillg�nglig: https://www.owasp.org/index.php/XSS_(Cross_Site_Scripting)_Prevention_Cheat_Sheet [H�mtad: 28 november, 2015]

[6] Steve Souders, "High Performance Websites: Essential knowledge for frontend engineers", O'Reilly, 2007

[5] John H�ggerud, "Laboration 02" Linn�universitetet, November 2015 [Online] Tillg�nglig: https://coursepress.lnu.se/kurs/webbteknik-ii/laborationer/laboration-02/ [H�mtad: 25 november, 2015]

[6] "Authentication Cheat Sheet" Open Web Application Security Project, November 2015. [Online] Tillg�nglig: https://www.owasp.org/index.php/Authentication_Cheat_Sheet  [H�mtad: 2 december 2015]

[7] Wikipedia, �Secure Sockets Layer�, 4 Februari 2015. [Online] Tillg�nglig: https://sv.wikipedia.org/wiki/Secure_Sockets_Layer [H�mtad: 2 decemder 2015]
