 /** Carrega bibliotecas necessarias **/
using OpenEdge.Net.HTTP.IHttpClientLibrary.
using OpenEdge.Net.HTTP.ConfigBuilder.
using OpenEdge.Net.HTTP.ClientBuilder.
using OpenEdge.Net.HTTP.Credentials.
using OpenEdge.Net.HTTP.IHttpClient.
using OpenEdge.Net.HTTP.IHttpRequest.
using OpenEdge.Net.HTTP.RequestBuilder.
using OpenEdge.Net.URI.
using OpenEdge.Net.HTTP.IHttpResponse.
using Progress.Json.ObjectModel.JsonObject.
using Progress.Json.ObjectModel.JsonArray.
using Progress.Json.ObjectModel.ObjectModelParser.

def VAR netClient        AS IHttpClient        no-undo.
def VAR netUri           as URI                no-undo.
def VAR netRequest       as IHttpRequest       no-undo.
def VAR netResponse      as IHttpResponse      no-undo.

DEF VAR joResponse       AS JsonObject NO-UNDO.
DEF VAR lcJsonRequest    AS LONGCHAR NO-UNDO.
DEF VAR lcJsonResponse   AS LONGCHAR NO-UNDO.

DEFINE VARIABLE joCNAE         AS JsonObject NO-UNDO.
DEFINE VARIABLE joGrupo         AS JsonObject NO-UNDO.
DEFINE VARIABLE joDivisao         AS JsonObject NO-UNDO.
DEFINE VARIABLE joSecao       AS JsonObject NO-UNDO. 
RUN LOG("INICIO DATABASE CNAECLASSE").
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


DEF INPUT PARAM TABLE FOR ttentrada.
DEF INPUT-OUTPUT PARAM TABLE FOR ttcnaeClasse.
def input param vtmp as char.
def output param vmensagem as char.

vmensagem = ?.

find first ttentrada no-error.
IF NOT AVAIL ttentrada 
THEN DO:
    vmensagem = "Dados de Entrada nao encontrados".
    return.  
END.
 //DISP string(ttentrada.cnaeID).

/* INI - requisicao web */
ASSIGN netClient   = ClientBuilder:Build():Client       
       netUri      = new URI("http", "servicodados.ibge.gov.br") /* URI("metodo", "dominio", "porta") */
       netUri:Path = "/api/v2/cnae/classes/" + string(ttentrada.cnaeID). 
       

//FAZ A REQUISIÇÃO
netRequest = RequestBuilder:GET (netUri)
                     :AcceptJson()
                     :REQUEST.

netResponse = netClient:EXECUTE(netRequest).

//TRATA RETORNO
if type-of(netResponse:Entity, JsonObject) then do:
    joResponse = CAST(netResponse:Entity, JsonObject).
    joResponse:Write(lcJsonResponse).
    //RUN LOG("RETORNO CNAE " + STRING(lcJsonResponse)).

    joCNAE = joResponse.
    joGrupo = joCNAE:GetJsonObject("grupo").
    joDivisao = joGrupo:GetJsonObject("divisao").
    joSecao = joDivisao:GetJsonObject("secao").
    CREATE ttcnaeClasse.
    ttcnaeClasse.ID = joCNAE:GetCharacter("id").
    ttcnaeClasse.Descricao = joCNAE:GetCharacter("descricao").
    ttcnaeClasse.grupoID = joGrupo:GetCharacter("id").
    ttcnaeClasse.grupoDescricao = joGrupo:GetCharacter("descricao").
    ttcnaeClasse.divisaoID = joDivisao:GetCharacter("id").
    ttcnaeClasse.divisaoDescricao = joDivisao:GetCharacter("descricao").
    ttcnaeClasse.secaoID = joSecao:GetCharacter("id").
    ttcnaeClasse.secaoDescricao = joSecao:GetCharacter("descricao").

        //busca cnaeSecao e caracTrib
        find cnaeSecao where idcnSecao = ttcnaeClasse.secaoID NO-LOCK NO-ERROR.
        IF AVAIL cnaeSecao 
        then do:
            find caracTrib where caracTrib.caracTrib = cnaeSecao.caracTrib NO-LOCK NO-ERROR.
            IF AVAIL caracTrib 
            THEN DO:
                ttcnaeClasse.caracTrib            = caracTrib.caracTrib.
                ttcnaeClasse.descricaoCaracTrib   = caracTrib.descricaoCaracTrib.
            END.
        END.
     RUN LOG("Criou ttcnaeClasse").
     IF (ttcnaeClasse.caracTrib = ?)
     THEN DO:
        RUN LOG("caracTrib: NULL"). 
     END.
     ELSE DO:
        RUN LOG("caracTrib: " + string(ttcnaeClasse.caracTrib)).
     END.
     
END.


procedure LOG.
    DEF INPUT PARAM vmensagem AS CHAR.    
    OUTPUT TO VALUE(vtmp + "/fisnota_processar_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " progress -> " vmensagem
            SKIP.
    OUTPUT CLOSE.
    
END PROCEDURE.







