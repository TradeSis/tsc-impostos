
DEF INPUT PARAM vlcentrada AS LONGCHAR.
DEF INPUT PARAM vtmp        AS CHAR.

def var hentrada as handle.             /* HANDLE ENTRADA */
def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hsaida   as handle.             /* HANDLE SAIDA */
def var vlcsaida   as longchar.         /* JSON SAIDA */
DEF VAR hprocessar AS HANDLE.
DEF VAR vlcProcessar AS LONGCHAR.

RUN LOG(" INICIO ").

def TEMP-TABLE ttentrada NO-UNDO serialize-name "dadosEntrada"  /* JSON ENTRADA */
    field idEmpresa      AS INT.

  
def TEMP-TABLE ttprocessar NO-UNDO serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idEmpresa      AS INT
    field idFornecimento      AS INT.
    
def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.
    

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.
IF NOT AVAIL ttentrada 
THEN DO:
    RUN montasaida (400,"Dados de entrada invalidos!").
    RETURN.
END.


for EACH geralfornecimento WHERE geralfornecimento.dataAtualizacaoTributaria = ? NO-LOCK:
        FIND geralproduto OF geralfornecimento NO-LOCK.
        if geralproduto.eanProduto = ?
        THEN NEXT. 
        
        CREATE ttprocessar.
        ttprocessar.idEmpresa       =  ttentrada.idEmpresa.
        ttprocessar.idFornecimento  =  geralfornecimento.idFornecimento.
        
        hprocessar  = temp-table ttprocessar:handle.
        
        lokJson = hprocessar:WRITE-JSON("LONGCHAR", vlcProcessar, TRUE).   
        
        RUN impostos/app/1/imendes_saneamento.p ( INPUT vlcProcessar,
                                                  INPUT vtmp).
                                                 
        FOR EACH ttprocessar:
            DELETE  ttprocessar.    
        END.  
           
end.

RUN montasaida (200," ").
RETURN.

PROCEDURE montasaida.
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
    OUTPUT TO VALUE(vtmp + "/imendes_saneamentoGeral_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " progress -> " vmensagem
            SKIP.
    OUTPUT CLOSE.
    
END PROCEDURE.

    
