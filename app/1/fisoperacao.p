def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idOperacao  like fisoperacao.idOperacao.
    /*field idAtividade  like fisoperacao.idAtividade
    field idNatureza  like fisoperacao.idNatureza
    field idProcesso  like fisoperacao.idProcesso
    field FiltroTipoOp  AS CHAR. */

def temp-table ttfisoperacao  no-undo serialize-name "fisoperacao"  /* JSON SAIDA */
    like fisoperacao
    FIELD nomeAtividade AS CHAR
    FIELD nomeProcesso AS CHAR
    FIELD nomeNatureza AS CHAR.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vidOperacao like ttentrada.idOperacao.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vidOperacao = 0.
if avail ttentrada
then do:
    vidOperacao = ttentrada.idOperacao.
    if vidOperacao = ? then vidOperacao = 0.
end.

IF ttentrada.idOperacao <> ? OR (ttentrada.idOperacao = ?)
THEN DO:
    for each fisoperacao where
    (if vidOperacao = 0
    then true /* TODOS */
    else fisoperacao.idOperacao = vidOperacao)
    no-lock.
    
    run criaOperacao.
    
    end.
END.

/*IF ttentrada.idAtividade <> ? OR ttentrada.idProcesso <> ? OR ttentrada.idNatureza <> ?
THEN DO:
    for each fisoperacao where
    fisoperacao.idAtividade = ttentrada.idAtividade AND
    fisoperacao.idProcesso = ttentrada.idProcesso AND
    fisoperacao.idNatureza = ttentrada.idNatureza 
    no-lock.
    
    run criaOperacao.
    
    end.
    
END.  */


find first ttfisoperacao no-error.

if not avail ttfisoperacao
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Operacao nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttfisoperacao:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).


PROCEDURE criaOperacao.

    FIND fisatividade WHERE fisatividade.idAtividade = fisoperacao.idAtividade NO-LOCK NO-ERROR.
    IF NOT AVAIL fisatividade
    THEN DO:
    RUN montasaida (400,"fisatividade.idAtividade nao encontrado").
    RETURN.
    END.
    FIND fisprocesso WHERE fisprocesso.idProcesso = fisoperacao.idProcesso NO-LOCK NO-ERROR.
    IF NOT AVAIL fisprocesso
    THEN DO:
    RUN montasaida (400,"fisprocesso.idProcesso nao encontrado").
    RETURN.
    END.
    FIND fisnatureza WHERE fisnatureza.idNatureza = fisoperacao.idNatureza NO-LOCK NO-ERROR.
    IF NOT AVAIL fisnatureza
    THEN DO:
    RUN montasaida (400,"fisnatureza.idNatureza nao encontrado").
    RETURN.
    END.

    create ttfisoperacao.
    ttfisoperacao.idOperacao    = fisoperacao.idOperacao.
    ttfisoperacao.nomeOperacao  = fisoperacao.nomeOperacao.
    ttfisoperacao.idAtividade   = fisoperacao.idAtividade.
    ttfisoperacao.idProcesso    = fisoperacao.idProcesso.
    ttfisoperacao.idGrupoOper   = fisoperacao.idGrupoOper.
    ttfisoperacao.idNatureza    = fisoperacao.idNatureza.
    ttfisoperacao.idEntSai      = fisoperacao.idEntSai.
    ttfisoperacao.xfop          = fisoperacao.xfop.
    ttfisoperacao.cfop          = fisoperacao.cfop.
    ttfisoperacao.nomeAtividade = fisatividade.nomeAtividade.
    ttfisoperacao.nomeProcesso  = fisprocesso.nomeProcesso.
    ttfisoperacao.nomeNatureza  = fisnatureza.nomeNatureza.
    

END.

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
