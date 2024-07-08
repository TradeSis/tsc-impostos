def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idNatureza  like fisnatureza.idNatureza.

def temp-table ttfisnatureza  no-undo serialize-name "fisnatureza"  /* JSON SAIDA */
    like fisnatureza.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidNatureza like ttentrada.idNatureza.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidNatureza = 0.
if avail ttentrada
then do:
    vidNatureza = ttentrada.idNatureza.
    if vidNatureza = ? then vidNatureza = 0.
end.

IF ttentrada.idNatureza <> ? OR (ttentrada.idNatureza = ?)
THEN DO:
    for each fisnatureza where
    (if vidNatureza = 0
    then true /* TODOS */
    else fisnatureza.idNatureza = vidNatureza)
    no-lock.
    
    create ttfisnatureza.
    BUFFER-COPY fisnatureza TO ttfisnatureza.
    
    end.
END.


find first ttfisnatureza no-error.

if not avail ttfisnatureza
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Natureza nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisnatureza:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).

