

def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */
//RUN LOG("INICIO").

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def TEMP-TABLE ttentrada_imendes NO-UNDO serialize-name "dadosEntrada"  /* JSON ENTRADA */
    field idEmpresa      AS INT
    field idFornecimento      AS INT.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as CHAR.

//---------- GRUPOS -------------    
def temp-table ttgrupos no-undo serialize-name "fiscalgrupo"   
    LIKE fiscalgrupo.

//---------- REGRAS -------------
def temp-table ttregra no-undo serialize-name "fiscalregra"   
    LIKE fiscalregra.

//---------- OPERACAO------------
def temp-table ttoperacao no-undo serialize-name "fiscaloperacao"   
    LIKE fiscaloperacao.                          

DEF VAR vmensagem AS CHAR.


hEntrada = temp-table ttentrada_imendes:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada_imendes no-error.
IF NOT AVAIL ttentrada_imendes 
THEN DO:
    RUN montasaida (400,"Dados de entrada invalidos!").
    RETURN.
END.

RUN impostos/database/imendes_saneamento.p (  input table ttentrada_imendes, 
                                              INPUT vtmp,
                                              output vmensagem).

