def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idNota  like fisnotatotal.idNota.

def temp-table ttfisnotatotal  no-undo serialize-name "fisnotatotal"  /* JSON SAIDA */
    like fisnotatotal.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidNota like ttentrada.idNota.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidNota = 0.
if avail ttentrada
then do:
    vidNota = ttentrada.idNota.
    if vidNota = ? then vidNota = 0.
end.

IF ttentrada.idNota <> ? OR (ttentrada.idNota = ?)
THEN DO:
    for each fisnotatotal where
    (if vidNota = 0
    then true /* TODOS */
    else fisnotatotal.idNota = vidNota)
    no-lock.
    
    create ttfisnotatotal.
    BUFFER-COPY fisnotatotal TO ttfisnotatotal.
    
    end.
END.


find first ttfisnotatotal no-error.

if not avail ttfisnotatotal
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "total nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisnotatotal:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).

