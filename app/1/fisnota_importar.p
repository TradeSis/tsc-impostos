/** Carrega bibliotecas necessarias **/
using Progress.Json.ObjectModel.JsonObject.
using Progress.Json.ObjectModel.JsonArray.
using Progress.Json.ObjectModel.ObjectModelParser.


define VARIABLE omParser  as ObjectModelParser no-undo.
define variable joEntrada  AS JsonObject no-undo.
define variable jonfeProc  AS JsonObject no-undo.
define variable joNFE  AS JsonObject no-undo.
define variable joinfNFe  AS JsonObject no-undo.
define variable joide  AS JsonObject no-undo.
define variable joemit  AS JsonObject no-undo.
define variable jodest  AS JsonObject no-undo.
define variable jototal  AS JsonObject no-undo.


def input PARAM vlcentrada as longchar. /* JSON ENTRADA */
def input PARAM vtmp       as char.     /* CAMINHO PROGRESS_TMP */
RUN LOG("INICIO").

def var vlcsaida   as longchar.         /* JSON SAIDA */
//DEF VAR lcAuxiliar AS LONGCHAR.

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

DEF VAR vidNota AS INT.
DEF VAR vmensagem AS CHAR.
DEF VAR vidPessoaEmitente AS INT.
DEF VAR vidPessoaDestinatario AS INT.
DEF VAR xmlPath AS CHAR.
DEF VAR xmlentrada as longchar. 

DEF VAR vxml AS CHAR.


def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    FIELD nomeXml  AS CHAR
    field idEmpresa  AS INT.

def temp-table ttsaida  no-undo SERIALIZE-NAME "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field nomeXml        AS CHAR FORMAT "x(30)"
    field descricao      as CHAR
    field qtd as int.

    
//---------- fisnota -------------    
def temp-table ttfisnota no-undo serialize-name "fisnota"   
    LIKE fisnota.
    
    
create ttsaida.
    nomexml = "TOTAL IMPORTADO".
    qtd = 0.
    descricao = string(qtd).
    create ttsaida.
    nomexml = "TOTAL CARREGADO".
    qtd = 0.
    descricao = string(qtd).
    create ttsaida.
    nomexml = "TOTAL ERRO".
    qtd = 0.
    descricao = string(qtd).

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
fix-codepage(xmlentrada) = "UTF-8".
FOR EACH ttentrada.
    // TTSAIDA - TOTAL IMPORTADO
    FIND FIRST ttsaida WHERE ttsaida.nomexml = "TOTAL IMPORTADO".
    ttsaida.qtd = ttsaida.qtd + 1.
    descricao = string(qtd).
        
    copy-lob from file ttentrada.nomeXml to xmlentrada.

    OS-DELETE VALUE(ttentrada.nomeXml).
      
    omParser = new Progress.Json.ObjectModel.ObjectModelParser().
    joEntrada = cast(omParser:Parse(xmlentrada), PROGRESS.Json.ObjectModel.JsonObject).
        
    jonfeProc = joEntrada:GetJsonObject("nfeProc") NO-ERROR.
    if type-of(jonfeProc, JsonObject) then do:
        joNFE = jonfeProc:GetJsonObject("NFe").
    END. 
    ELSE DO:
        joNFE = joEntrada:GetJsonObject("NFe").
    END.

    joinfNFe = joNFE:GetJsonObject("infNFe").
    joide = joinfNFe:GetJsonObject("ide").
    joemit = joinfNFe:GetJsonObject("emit").
    jodest = joinfNFe:GetJsonObject("dest").
    jototal = joinfNFe:GetJsonObject("total").


    //joemit:Write(lcAuxiliar).
    //MESSAGE STRING(lcAuxiliar) VIEW-AS ALERT-BOX.

    FIND empresa WHERE empresa.idEmpresa = ttentrada.idEmpresa NO-LOCK NO-ERROR.
    IF empresa.cnpj <> joemit:GetCharacter("CNPJ") AND empresa.cnpj <> jodest:GetCharacter("CNPJ")
    THEN DO:   
       //RUN montasaida (400,"Somente NFE da empresa Padrao permitido").
       NEXT.    
    END.     

    
    IF joide:GetCharacter("tpNF") <> "1" AND joide:GetCharacter("finNFe") <> "1"
    THEN DO:
       //RUN montasaida (400,"NFE fora do padrao " + "tpNF=" + joide:GetCharacter("tpNF") +
                                                //"finNFE=" + joide:GetCharacter("finNFe") ).
       //NEXT.
       
        // TTSAIDA - TOTAL ERRO
        FIND FIRST ttsaida WHERE ttsaida.nomexml = "TOTAL ERRO".
        ttsaida.qtd = ttsaida.qtd + 1.
        descricao = string(qtd).

        create ttsaida.
        ttsaida.nomexml = STRING(joinfNFe:GetJsonObject("@attributes"):GetCharacter("Id")).
        ttsaida.descricao = "NFE fora do padrao".
        
        NEXT.
    END.
    
    // TTSAIDA - TOTAL CARREGADO
    FIND FIRST ttsaida WHERE ttsaida.nomexml = "TOTAL CARREGADO".
    ttsaida.qtd = ttsaida.qtd + 1.
    ttsaida.descricao = string(qtd).
     

    //---------- FISNOTA -------------
    CREATE ttfisnota.
    ttfisnota.chaveNFe          =     joinfNFe:GetJsonObject("@attributes"):GetCharacter("Id").
    ttfisnota.naturezaOp        =     joide:GetCharacter("natOp").
    ttfisnota.modelo            =     joide:GetCharacter("mod"). 
    ttfisnota.serie             =     joide:GetCharacter("serie").
    ttfisnota.NF                =     joide:GetCharacter("nNF").
    ttfisnota.dtEmissao         =     joide:GetDateTime("dhEmi"). 
    //ttfisnota.idPessoaEmitente  =  vidPessoaEmitente.
    //ttfisnota.idPessoaDestinatario  = vidPessoaDestinatario.
    ttfisnota.vNF               =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vNF")).
    ttfisnota.vProd             =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vProd")).
    ttfisnota.vFrete            =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vFrete")).
    ttfisnota.vSeg              =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vSeg")).
    ttfisnota.vDesc             =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vDesc")).
    ttfisnota.vOutro            =     decimal(jototal:GetJsonObject("ICMSTot"):GetCharacter("vOutro")).
    vidNota = 0.



    RUN impostos/database/fisnota.p (INPUT "PUT", 
                                     input table ttfisnota,
                                     INPUT xmlentrada,
                                     output vidNota,
                                     output vmensagem).
    DELETE ttfisnota.
   /* if vmensagem <> ? then do:
        RUN montasaida (400,vmensagem).
        NEXT.
    end.  */
    

END.

       
//procedure montasaida.
//DEF INPUT PARAM tnomexml AS CHAR.
//DEF INPUT PARAM tdescricao AS CHAR.
//DEF INPUT PARAM tqtd AS INT.

//create ttsaida.
//ttsaida.nomexml = tnomexml.
//ttsaida.descricao = tdescricao.
//ttsaida.qtd = tqtd.

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).

//END PROCEDURE.


procedure LOG.
    DEF INPUT PARAM vmensagem AS CHAR.    
    OUTPUT TO VALUE(vtmp + "/fisnota_importar_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " progress -> " vmensagem
            SKIP.
    OUTPUT CLOSE.
    
END PROCEDURE.


