def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

{impostos/database/acentos.i}


def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field caracTrib like caracTrib.caracTrib.

def temp-table ttcaracTrib  no-undo serialize-name "caracTrib"  /* JSON SAIDA */
    field caracTrib             like caracTrib.caracTrib
    field descricaoCaracTrib    like caracTrib.descricaoCaracTrib.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vcaracTrib like ttentrada.caracTrib.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


vcaracTrib = ?.
if avail ttentrada
then do:
    vcaracTrib = ttentrada.caracTrib.
end.

IF ttentrada.caracTrib <> ? OR ttentrada.caracTrib = ?
THEN DO:
    for each caracTrib where
        (if vcaracTrib = ?
         then true /* TODOS */
         else caracTrib.caracTrib = vcaracTrib)
         no-lock.

         create ttcaracTrib.
         ttcaracTrib.caracTrib   = caracTrib.caracTrib.
         ttcaracTrib.descricaoCaracTrib  = removeacento(caracTrib.descricaoCaracTrib).
    end.
END.


find first ttcaracTrib no-error.

if not avail ttcaracTrib
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "caracTrib nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttcaracTrib:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
return string(vlcSaida).


