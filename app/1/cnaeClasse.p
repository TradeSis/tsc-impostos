

def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */
RUN LOG("INICIO").

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def TEMP-TABLE ttentrada NO-UNDO serialize-name "dadosEntrada"  /* JSON ENTRADA */
    field cnaeID      AS INT.

def temp-table ttcnaeClasse  no-undo serialize-name "cnaeClasse"  /* JSON SAIDA */
    field ID                    as CHAR
    field Descricao             as CHAR
    field grupoID               as CHAR
    field grupoDescricao        as CHAR
    field divisaoID             as CHAR
    field divisaoDescricao      as CHAR
    field secaoID               as CHAR
    field secaoDescricao        as CHAR
    field caracTrib             AS INT init ?
    field descricaoCaracTrib    as CHAR.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as CHAR.

def var vmensagem as char.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.
IF NOT AVAIL ttentrada 
THEN DO:
    RUN montasaida (400,"Dados de entrada invalidos!").
    RETURN.
END.


RUN impostos/database/cnaeClasse.p (  input table ttentrada,
                                      INPUT-OUTPUT table ttcnaeClasse, 
                                      INPUT vtmp,
                                      output vmensagem).



find first ttcnaeClasse no-error.

if not avail ttcnaeClasse
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "cnaeClasse nao encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttcnaeClasse:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


procedure montasaida.
    DEF INPUT PARAM tstatus AS INT.
    DEF INPUT PARAM tdescricaoStatus AS CHAR.

    create ttsaida.
    ttsaida.tstatus = tstatus.
    ttsaida.descricaoStatus = tdescricaoStatus.

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    put unformatted string(vlcSaida).

END PROCEDURE.


procedure LOG.
    DEF INPUT PARAM vmensagem AS CHAR.    
    OUTPUT TO VALUE(vtmp + "/cnae_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " progress -> " vmensagem
            SKIP.
    OUTPUT CLOSE.
    
END PROCEDURE.
