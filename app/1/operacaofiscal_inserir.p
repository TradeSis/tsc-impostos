def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fiscaloperacao"   /* JSON ENTRADA */
    LIKE fiscaloperacao.
 

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char
    field idoperacaofiscal      AS INT SERIALIZE-NAME "idoperacaofiscal".

def var vmensagem as char.
def var vidoperacaofiscal as char.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

RUN impostos/database/operacaofiscal.p (    input "PUT",
                                            input table ttentrada, 
                                            output vidoperacaofiscal,
                                            output vmensagem).


IF vmensagem <> ? 
THEN DO:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = vmensagem.

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
END.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Operacao criada com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
