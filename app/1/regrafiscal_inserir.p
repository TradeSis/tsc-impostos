def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fiscalregra"   /* JSON ENTRADA */
    LIKE fiscalregra.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as CHAR serialize-name "retorno"
    field idregra      AS INT SERIALIZE-NAME "idRegra".
    
def VAR vidregra AS INT.
def var vmensagem as char.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidregra = 0.
RUN impostos/database/regrafiscal.p (   input "PUT",
                                        input table ttentrada, 
                                        output vidregra,
                                        output vmensagem).

if vmensagem <> ?
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = vmensagem.

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.



create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Regra criada com sucesso".
ttsaida.idregra = vidregra.

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
