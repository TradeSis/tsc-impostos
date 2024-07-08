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

DEFINE VARIABLE hRequest AS HANDLE NO-UNDO.
DEFINE VARIABLE hResponse AS HANDLE NO-UNDO.

DEFINE VARIABLE joRequest AS JsonObject NO-UNDO.
DEFINE VARIABLE jaRequest AS JsonArray NO-UNDO.
DEFINE VARIABLE joResponse AS JsonObject NO-UNDO.
DEFINE VARIABLE jaResponse AS JsonArray NO-UNDO.
DEFINE VARIABLE lReturnValue AS LOGICAL NO-UNDO.
DEFINE VARIABLE lcJsonRequest    AS LONGCHAR NO-UNDO.
DEFINE VARIABLE lcJsonResponse   AS LONGCHAR NO-UNDO.
  
   
DEFINE VARIABLE joImendes      AS JsonObject NO-UNDO.
DEFINE VARIABLE joEmit         AS JsonObject NO-UNDO.
DEFINE VARIABLE joPerfil       AS JsonObject NO-UNDO.                                                  
DEFINE VARIABLE jaUF           AS JsonArray NO-UNDO.
DEFINE VARIABLE jaProdutos     AS JsonArray NO-UNDO.                                                  
DEFINE VARIABLE joProduto      AS JsonObject NO-UNDO.
DEFINE VARIABLE jaCarac        AS JsonArray NO-UNDO.

DEF VAR joCabecalho         AS  JsonObject NO-UNDO.
DEF VAR jaGrupos         AS  JsonArray NO-UNDO.
DEF VAR joGrupo             AS  JsonObject.
DEF VAR lcJsonauxiliar      AS   LONGCHAR NO-UNDO.
DEF VAR jaRegras            AS  JsonArray NO-UNDO.
DEF VAR joRegra             AS  JsonObject NO-UNDO.
DEF VAR jauFs            AS  JsonArray NO-UNDO. 
DEF VAR jouF             AS  JsonObject NO-UNDO.
DEF VAR joCFOP      AS  JsonObject NO-UNDO.
DEF VAR jacaracTib     AS  JsonArray NO-UNDO.
DEF VAR jocaracTib      AS  JsonObject NO-UNDO.
DEF VAR japrodEan     AS  JsonArray NO-UNDO. 

RUN LOG("INICIO DATABASE IMENDES").
def var vlcsaida   as longchar. 
def var lokjson as log.  
def var hsaida   as handle. 

def TEMP-TABLE ttentrada NO-UNDO serialize-name "dadosEntrada"  /* JSON ENTRADA */
    field idEmpresa      AS INT
    field idFornecimento      AS INT.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as CHAR.

//---------- HISTORICO - REMOVIDO TABELA----------- 

// def temp-table ttfishistorico no-undo serialize-name "apifiscalhistorico"   
    //LIKE apifiscalhistorico. 
    
//---------- GRUPOS -------------    
def temp-table ttgrupos no-undo serialize-name "fiscalgrupo"   
    LIKE fiscalgrupo.

//---------- REGRAS -------------
def temp-table ttregra no-undo serialize-name "fiscalregra"   
    LIKE fiscalregra.

//---------- OPERACAO------------
def temp-table ttoperacao no-undo serialize-name "fiscaloperacao"   
    LIKE fiscaloperacao.                          

DEF BUFFER bgeralpessoasfornecedor FOR geralpessoas.
 
DEF VAR vsimplesN AS CHAR.
DEF VAR vcodRegra AS CHAR.
DEF VAR vcodExcecao AS CHAR.
DEF VAR vidRegra AS INT.
DEF VAR vcodigoGrupo AS CHAR.
DEF VAR vcodigoEstado AS CHAR.
DEF VAR vcFOP AS CHAR.
DEF VAR vcodigoCaracTrib AS CHAR.
DEF VAR vfinalidade AS CHAR.
DEF VAR vidGrupo AS INT.

DEF VAR vidoperacaofiscal AS INT.
DEF VAR vdtVigIni AS CHAR.
DEF VAR vdtVigFin AS CHAR.
DEF VAR vcodigoNcm AS CHAR.
DEF VAR vcodigoCest AS CHAR.
DEF VAR vidHistorico AS INT.
DEF VAR veanProduto LIKE geralprodutos.eanProduto.
DEF VAR vapifiscalcfop AS CHAR  NO-UNDO.
DEF VAR vapifiscalorigem AS INT NO-UNDO.


//variaveis de contador
DEF VAR iGrupos AS INT.   
DEF VAR iRegras AS INT.
DEF VAR iuFs AS INT.
DEF VAR icaracTib AS INT.
DEF VAR iprodEan AS INT.

DEF INPUT PARAM TABLE FOR ttentrada.
def input param vtmp as char.
def output param vmensagem as char.

vmensagem = ?.

find first ttentrada no-error.
IF NOT AVAIL ttentrada 
THEN DO:
    RUN montasaida (400,"Dados de entrada invalidos!").
    RETURN.
END.

 
/* APIFISCAL */
FIND apifiscal WHERE apifiscal.idEmpresa = ttentrada.idempresa AND apifiscal.fornecedor = "imendes" NO-LOCK.

IF apifiscal.login = ?  
THEN DO:
    RUN montasaida (400,"Campo login Invalido").
    RETURN.
END.

IF apifiscal.senha = ?  
THEN DO:
    RUN montasaida (400,"Campo senha Invalido").
    RETURN.
END.

IF apifiscal.tpAmb = ?  
THEN DO:
    RUN montasaida (400,"Campo tpAmb Invalido").
    RETURN.
END.

IF apifiscal.cfopEntrada = ?  
THEN DO:
    RUN montasaida (400,"Campo cfopEntrada Invalido").
    RETURN.
END.

IF apifiscal.finalidade = ?  
THEN DO:
    RUN montasaida (400,"Campo finalidade Invalido").
    RETURN.
END.
 
        
/* EMPRESA */
FIND empresa WHERE empresa.idEmpresa = ttentrada.idEmpresa NO-LOCK NO-ERROR.  
IF NOT AVAIL empresa 
THEN DO:
    RUN montasaida (400,"Empresa não cadastrada!").
    RETURN.
END.
 
/* GERAL PESSOAS */
FIND geralpessoas WHERE geralpessoas.cpfCnpj = empresa.cnpj NO-LOCK.
IF NOT AVAIL geralpessoas 
THEN DO:
    RUN montasaida (400,"Geralpessoas não encontrada!").
    RETURN.
END.

IF geralpessoas.cnae = ?  
THEN DO:
    RUN montasaida (400,"Campo cnae Invalido").
    RETURN.
END.

IF geralpessoas.regimeEspecial = ?  
THEN DO:
    RUN montasaida (400,"Campo regimeEspecial Invalido").
    RETURN.
END.

IF geralpessoas.regimeTrib = ?  
THEN DO:
    RUN montasaida (400,"Campo regimeTrib Invalido").
    RETURN.
END.

IF geralpessoas.crt = ?  
THEN DO:
    RUN montasaida (400,"Campo crt Invalido").
    RETURN.
END.

vsimplesN = "".

IF  geralpessoas.regimeTrib = 'SN' 
THEN DO:
    vsimplesN = "S".
END.
ELSE DO:
   vsimplesN = "N".
END.    

RUN LOG("Vai montar json").

/* JSON DE REQUEST */       
joEmit = NEW JsonObject().
joEmit:ADD("amb",apifiscal.tpAmb).  
joEmit:ADD("cnpj",apifiscal.login).  //geralpessoas.cpfCnpj
joEmit:ADD("crt",geralpessoas.crt).
joEmit:ADD("regimeTrib",geralpessoas.regimeTrib). 
joEmit:ADD("uf",geralpessoas.codigoEstado).
joEmit:ADD("cnae",geralpessoas.cnae).
joEmit:ADD("regimeEspecial",geralpessoas.regimeEspecial).
joEmit:ADD("substlCMS","N").  // - Verificar com Daniel
joEmit:ADD("interdependente","N").  // - Verificar com Daniel                                                   

jaUF = NEW JsonArray().
jaCarac = NEW JsonArray().

/* GERAL PRODUTOS */
jaProdutos = NEW JsonArray().

vapifiscalcfop  = apifiscal.cfopEntrada.
vapifiscalorigem = apifiscal.origem.

/* GERAL FORNECIMENTO */
FIND geralfornecimento WHERE geralfornecimento.idFornecimento = ttentrada.idFornecimento NO-LOCK NO-ERROR.
IF NOT AVAIL geralfornecimento 
THEN DO:
    RUN montasaida (400,"idFornecimento não encontrado!").
    RETURN.
END.   

/* GERAL PRODUTOS */
FIND geralprodutos WHERE geralprodutos.idGeralProduto = geralfornecimento.idGeralProduto NO-LOCK NO-ERROR.
IF NOT AVAIL geralprodutos 
THEN DO:
    RUN montasaida (400,"idGeralProduto não encontrado!").
    RETURN.
END.

RUN LOG("idFornecimento Recebido " + STRING(ttentrada.idFornecimento)).    
RUN LOG("idGeralProduto Recebido " + STRING(geralprodutos.idGeralProduto)).
    
/* FISCAL GRUPO */
vcodigoNcm = "".
find fiscalgrupo where fiscalgrupo.idGrupo = geralprodutos.idGrupo  no-lock no-error.
if avail fiscalgrupo then do:
    vcodigoNcm =  fiscalgrupo.codigoNcm.
    vidgrupo = fiscalgrupo.idgrupo.
end.
           
FIND bgeralpessoasfornecedor WHERE bgeralpessoasfornecedor.cpfCnpj = geralfornecimento.Cnpj NO-LOCK.
    jaUF:ADD(bgeralpessoasfornecedor.codigoEstado).
    jaCarac:ADD(bgeralpessoasfornecedor.caracTrib).
   
IF  geralfornecimento.cfop <> ?
THEN DO:
    vapifiscalcfop = geralfornecimento.cfop.
     
END.
IF  geralfornecimento.origem <> ?
THEN DO:
    vapifiscalorigem = geralfornecimento.origem.
END.
        
joProduto = NEW JsonObject().
joProduto:ADD("codigo",STRING(geralprodutos.eanProduto)).  
joProduto:ADD("codInterno","N").
joProduto:ADD("descricao",geralprodutos.nomeProduto).
joProduto:ADD("ncm",vcodigoNcm).
    
jaProdutos:ADD(joProduto).

joPerfil = NEW JsonObject().
joPerfil:ADD("uf",jaUF).
joPerfil:ADD("cfop",vapifiscalcfop).
joPerfil:ADD("caracTrib",jaCarac). 
joPerfil:ADD("finalidade",apifiscal.finalidade).
joPerfil:ADD("simplesN",vsimplesN).
joPerfil:ADD("origem",vapifiscalorigem). 
joPerfil:ADD("substlCMS","N").
joPerfil:ADD("prodZFM","N").


joImendes = NEW JsonObject().
joImendes:ADD("emit",joEmit).
joImendes:ADD("perfil",joPerfil).
joImendes:ADD("produtos",jaProdutos).


joImendes:Write(lcJsonRequest).
//MESSAGE STRING(lcJsonauxiliar) view-as alert-box.
  OUTPUT TO VALUE(vtmp + "/imendes_Saneamento_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " JSON -> " STRING(lcJsonRequest)
            SKIP.
    OUTPUT CLOSE.

/* INI - requisicao web */
ASSIGN netClient   = ClientBuilder:Build():Client       
       netUri      = new URI("http", "consultatributos.com.br",8080) /* URI("metodo", "dominio", "porta") */
       netUri:Path = "/api/v3/public/SaneamentoGrades".     
       

//FAZ A REQUISIÇÃO
// ANTIGO netRequest  = RequestBuilder:POST(netUri, joImendes):REQUEST.
netRequest = RequestBuilder:POST (netUri, joImendes)
                     :AcceptJson()
                     :AddHeader("login", apifiscal.login)
                     :AddHeader("senha", apifiscal.senha)
                     :ContentType("application/json":U)
                     :REQUEST.

netResponse = netClient:EXECUTE(netRequest).

//TRATA RETORNO
if type-of(netResponse:Entity, JsonObject) then do:
    joResponse = CAST(netResponse:Entity, JsonObject).
    joResponse:Write(lcJsonResponse).
    RUN LOG("RETORNO IMENDES " + STRING(lcJsonResponse)).
    
    joCabecalho = joResponse:GetJsonObject("Cabecalho").
    /* REMOVIDO TABELA HISTORICO    
    CREATE ttfishistorico.
    ttfishistorico.dtHistorico = DATETIME(TODAY, MTIME).
    ttfishistorico.sugestao       =     joCabecalho:GetCharacter("sugestao").
    ttfishistorico.amb       =     joCabecalho:GetInteger("amb").
    ttfishistorico.cnpj       =     joCabecalho:GetCharacter("cnpj").
    ttfishistorico.dthr       =    joCabecalho:GetDateTime("dthr").
    ttfishistorico.transacao  =     joCabecalho:GetCharacter("transacao").
    ttfishistorico.mensagem  =     joCabecalho:GetCharacter("mensagem").
    ttfishistorico.mensagem  =     joCabecalho:GetCharacter("mensagem").
    ttfishistorico.prodEnv  =     joCabecalho:GetInteger("prodEnv").
    ttfishistorico.prodRet  =     joCabecalho:GetInteger("prodRet").
    ttfishistorico.prodNaoRet  =     joCabecalho:GetInteger("prodNaoRet").
    ttfishistorico.comportamentosParceiro  =     joCabecalho:GetCharacter("comportamentosParceiro").
    ttfishistorico.comportamentosCliente  =     joCabecalho:GetCharacter("comportamentosCliente").
    ttfishistorico.versao  =     joCabecalho:GetCharacter("versao").
    ttfishistorico.duracao  =     joCabecalho:GetCharacter("duracao").
    vidHistorico = 0.
    RUN impostos/database/fishistorico.p (  INPUT "PUT",
                                            input table ttfishistorico, 
                                            output vidHistorico,
                                            output vmensagem).
    DELETE ttfishistorico.
    if vmensagem <> ? then do:
        RUN montasaida (400,vmensagem).
        RETURN.
    end.
    find apifiscalhistorico where apifiscalhistorico.idHistorico = vidHistorico no-lock.
    */ 

     /* leitura de grupos */
    jaGrupos = joResponse:GetJsonArray("Grupos").
    
    DO iGrupos = 1 to jaGrupos:length on error undo, next:
        joGrupo = jaGrupos:GetJsonObject(iGrupos).

        vcodigoGrupo = joGrupo:GetCharacter("codigo").
                 
        IF vcodigoGrupo = ?  
        THEN DO:
            RUN montasaida (400,"Codigo do Grupo Invalido").
            RETURN.
        END.
         
        find fiscalgrupo where fiscalgrupo.codigoGrupo = vcodigoGrupo  no-lock no-error.
        if avail fiscalgrupo then do:
            vidgrupo = fiscalgrupo.idgrupo.            
        end.
        else do:
            CREATE ttgrupos.
            ttgrupos.codigoGrupo = joGrupo:GetCharacter("codigo").
            ttgrupos.nomeGrupo = joGrupo:GetCharacter("descricao").
            ttgrupos.codigoNcm = joGrupo:GetCharacter("nCM").
            ttgrupos.codigoCest = joGrupo:GetCharacter("cEST").
            ttgrupos.impostoImportacao = joGrupo:GetDecimal("impostoImportacao").   
            ttgrupos.piscofinscstEnt = joGrupo:GetJsonObject("pisCofins"):GetCharacter("cstEnt").
            ttgrupos.piscofinscstSai = joGrupo:GetJsonObject("pisCofins"):GetCharacter("cstSai").
            ttgrupos.aliqPis = joGrupo:GetJsonObject("pisCofins"):GetDecimal("aliqPis").
            ttgrupos.aliqCofins = joGrupo:GetJsonObject("pisCofins"):GetDecimal("aliqCofins").
            ttgrupos.nri = joGrupo:GetJsonObject("pisCofins"):GetCharacter("nri").
            ttgrupos.ampLegal = joGrupo:GetJsonObject("pisCofins"):GetCharacter("ampLegal").
            ttgrupos.redPis = joGrupo:GetJsonObject("pisCofins"):GetDecimal("redPis").
            ttgrupos.redCofins = joGrupo:GetJsonObject("pisCofins"):GetDecimal("redCofins").
            ttgrupos.ipicstEnt = joGrupo:GetJsonObject("iPI"):GetCharacter("cstEnt").
            ttgrupos.ipicstSai = joGrupo:GetJsonObject("iPI"):GetCharacter("cstSai").
            ttgrupos.aliqipi = joGrupo:GetJsonObject("iPI"):GetDecimal("aliqipi").  
            ttgrupos.codenq = joGrupo:GetJsonObject("iPI"):GetCharacter("codenq").
            ttgrupos.ipiex = joGrupo:GetJsonObject("iPI"):GetCharacter("ex").
            vidgrupo = 0.
            RUN admin/database/grupoproduto.p (  INPUT "PUT",
                                                    input table ttgrupos, 
                                                    output vidgrupo,
                                                    output vmensagem).
            DELETE ttgrupos.
            if vmensagem <> ? then do:
                RUN montasaida (400,vmensagem).
                RETURN.
            end.
            find fiscalgrupo where fiscalgrupo.idgrupo = vidgrupo no-lock.
        END.
        
        jaRegras = joGrupo:GetJsonArray("Regras").
         //jaRegras:Write(lcJsonauxiliar, TRUE).
         //MESSAGE STRING(lcJsonauxiliar) view-as alert-box.
        DO iRegras = 1 to jaRegras:length on error undo, next:
            joRegra = jaRegras:GetJsonObject(iRegras).
            
            jauFs = joRegra:GetJsonArray("uFs").
         
            DO iuFs = 1 to jauFs:length on error undo, next:
                jouF = jauFs:GetJsonObject(iuFs).
                vcodigoEstado = jouF:GetCharacter("uF").                            
                IF vcodigoEstado = ? 
                THEN DO:
                    RUN montasaida (400,"Codigo do Estato Invalido").
                    RETURN.

                END.
                    
                joCFOP = jouF:GetJsonObject("CFOP").
                vcFOP = joCFOP:GetCharacter("cFOP").                                
                IF vcFOP = ? 
                THEN DO:
                    RUN montasaida (400,"Codigo do CFOP Invalido").
                    RETURN.
                END.                            
                jacaracTib = joCFOP:GetJsonArray("CaracTrib").
                                 
                DO icaracTib = 1 to jacaracTib:length on error undo, next:
                    jocaracTib = jacaracTib:GetJsonObject(icaracTib).
                                        
                    vcodigoCaracTrib = jocaracTib:GetCharacter("codigo").
                    vfinalidade = jocaracTib:GetCharacter("finalidade").
                    IF vcodigoCaracTrib = ? OR vfinalidade = ?
                    THEN DO:
                        RUN montasaida (400,"Codigo do codigoCaracTrib/finalidade Invalido").
                        RETURN.
                    END.                            
                    
                    vcodRegra = jocaracTib:GetCharacter("codRegra").
                    vcodExcecao = STRING(jocaracTib:GetInteger("codExcecao")).
                    
                    IF vcodRegra = ? OR vcodExcecao = ? 
                    THEN DO:
                        RUN montasaida (400,"Codigo do codRegra/codExcecao Invalido").
                        RETURN.                         
                    END.
                    
                    FIND fiscalregra where  fiscalregra.codRegra = vcodRegra AND 
                                            fiscalregra.codExcecao = vcodExcecao  
                                            no-lock no-error.
                    IF avail fiscalregra
                    then do:
                        vidRegra = fiscalregra.idRegra.
                    end.
                    else do:
                        CREATE ttregra.
                        ttregra.codRegra = jocaracTib:GetCharacter("codRegra").
                        ttregra.codExcecao = vcodExcecao.
                        ttregra.dtVigIni = date(jocaracTib:GetCharacter("dtVigIni")).
                        ttregra.dtVigFin = date(jocaracTib:GetCharacter("dtVigFin")).
                        ttregra.cFOPCaracTrib = jocaracTib:GetCharacter("cFOP").
                        ttregra.cST = jocaracTib:GetCharacter("cST").
                        ttregra.cSOSN = jocaracTib:GetCharacter("cSOSN").
                        ttregra.aliqIcmsInterna = jocaracTib:GetDecimal("aliqIcmsInterna").
                        ttregra.aliqIcmsInterestadual = jocaracTib:GetDecimal("aliqIcmsInterestadual").
                        ttregra.reducaoBcIcms = jocaracTib:GetDecimal("reducaoBcIcms").
                        ttregra.reducaoBcIcmsSt = jocaracTib:GetDecimal("reducaoBcIcmsSt").
                        ttregra.redBcICMsInterestadual = jocaracTib:GetDecimal("redBcICMsInterestadual").
                        ttregra.aliqIcmsSt = jocaracTib:GetDecimal("aliqIcmsSt").
                        ttregra.iVA = jocaracTib:GetDecimal("iVA").
                        ttregra.iVAAjust = jocaracTib:GetDecimal("iVAAjust").
                        ttregra.fCP = jocaracTib:GetDecimal("fCP").
                        ttregra.codBenef = jocaracTib:GetCharacter("codBenef").
                        ttregra.pDifer = jocaracTib:GetDecimal("pDifer").
                        ttregra.pIsencao = jocaracTib:GetDecimal("pIsencao").
                        ttregra.antecipado = jocaracTib:GetCharacter("antecipado").
                        ttregra.desonerado = jocaracTib:GetCharacter("desonerado").
                        ttregra.pICMSDeson = jocaracTib:GetDecimal("pICMSDeson").
                        ttregra.isento = jocaracTib:GetCharacter("isento").
                        ttregra.tpCalcDifal = jocaracTib:GetInteger("tpCalcDifal").
                        ttregra.ampLegal = jocaracTib:GetCharacter("ampLegal").
                        //ttregra.Protocolo = jocaracTib:GetCharacter("Protocolo").
                        //ttregra.Convenio = jocaracTib:GetCharacter("Convenio").
                        ttregra.regraGeral = jocaracTib:GetCharacter("regraGeral").
                        
                        vidRegra = 0.
                        RUN impostos/database/regrafiscal.p (   INPUT "PUT",
                                                                input table ttregra, 
                                                                output vidRegra,
                                                                output vmensagem).
                        DELETE ttregra.
                        if vmensagem <> ? then do:
                            RUN montasaida (400,vmensagem).
                            RETURN.
                        end.
                        find fiscalregra where fiscalregra.idRegra = vidRegra no-lock.
                    end.   
              
                    find fiscaloperacao where 
                                        fiscaloperacao.idGrupo = vidgrupo AND
                                        fiscaloperacao.codigoEstado = vcodigoEstado AND 
                                        fiscaloperacao.cFOP = vcFOP AND 
                                        fiscaloperacao.codigoCaracTrib = vcodigoCaracTrib AND 
                                        fiscaloperacao.finalidade = vfinalidade  
                                        no-lock no-error.
                    IF NOT avail fiscaloperacao
                    then do:
                        CREATE ttoperacao.
                        ttoperacao.idGrupo = vidgrupo.
                        ttoperacao.codigoEstado = vcodigoEstado.
                        ttoperacao.cFOP = vcFOP.
                        ttoperacao.codigoCaracTrib = vcodigoCaracTrib.
                        ttoperacao.finalidade = vfinalidade.
                        ttoperacao.idRegra = vidRegra.
                        
                        vidoperacaofiscal = 0.
                        RUN impostos/database/operacaofiscal.p (    INPUT "PUT",
                                                                    INPUT table ttoperacao, 
                                                                    output vidoperacaofiscal,
                                                                    output vmensagem).
                        DELETE ttoperacao.
                        if vmensagem <> ? then do:
                            RUN montasaida (400,vmensagem).
                            RETURN.
                        end.
                    end.
                end.  /* icaracTib */  
            end.  /* iuFs */
        end. /* iRegras */
        
        japrodEan = joGrupo:GetJsonArray("prodEan").
         DO iprodEan = 1 to japrodEan:length on error undo, next:
            veanProduto = int64(japrodEan:GetCharacter(iprodEan)).
            RUN LOG("eanProduto " + string(veanProduto)).
            
            DO ON ERROR UNDO:
                FIND geralprodutos WHERE geralprodutos.eanProduto = veanProduto EXCLUSIVE.
                geralprodutos.idGrupo = vidgrupo.
                
                FIND geralfornecimento WHERE geralfornecimento.idFornecimento = ttentrada.idFornecimento EXCLUSIVE.
                geralfornecimento.dataAtualizacaoTributaria = DATETIME(TODAY, MTIME).
                RUN LOG("DATA ATUALIZACAO " + string(geralfornecimento.dataAtualizacaoTributaria)).
            end.
            
         END. /* iprodEan */
            
    end. /* iGrupos */ 
END.

/*
if type-of(netResponse:Entity, JsonArray) then do:
    jaResponse = CAST(netResponse:Entity, JsonArray).
    jaResponse:Write(lcJsonResponse).
END.
*/
/* criar ttsaida */

/* PUT UNFORMATTED string(lcJsonResponse). */

RUN montasaida (200,"").
RETURN.


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
    OUTPUT TO VALUE(vtmp + "/imendes_Saneamento_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " progress -> " vmensagem
            SKIP.
    OUTPUT CLOSE.
    
END PROCEDURE.
