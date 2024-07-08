/** Carrega bibliotecas necessarias **/
using Progress.Json.ObjectModel.JsonObject.
using Progress.Json.ObjectModel.JsonArray.
using Progress.Json.ObjectModel.ObjectModelParser.

define VARIABLE omParser  as ObjectModelParser no-undo.
define variable joEntrada  AS JsonObject no-undo.
define variable jonfeProc  AS JsonObject no-undo.
define variable joNFE  AS JsonObject no-undo.
define variable joinfNFe  AS JsonObject no-undo.
define variable jadet  AS JsonArray no-undo.
define variable jodet  AS JsonObject no-undo.
define variable jototal  AS JsonObject no-undo.
define variable jonomeTotal  AS JsonObject no-undo.
define variable joimposto  AS JsonObject no-undo.
define variable joImpostos  AS JsonObject no-undo.
define variable jonomeImposto  AS JsonObject no-undo.
define variable joicms  AS JsonObject no-undo.  
define variable joprod  AS JsonObject no-undo. 
define variable joemit  AS JsonObject no-undo.
define variable jodest  AS JsonObject no-undo.

DEF VAR  lcJsonAuxiliar as longchar.

DEF input PARAM  vlcentrada as longchar. /* JSON ENTRADA */
DEF input PARAM  vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */
DEF VAR lcAuxiliar AS LONGCHAR.

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

DEF VAR vmensagem AS CHAR.
DEF VAR vidPessoaEmitente AS INT.
DEF VAR vidProduto AS INT.
DEF VAR vidGeralProduto AS INT.
DEF VAR vidNota AS INT.
DEF VAR vpercentual AS CHAR.
DEF VAR vvalor AS CHAR.
DEF VAR vidfornecimento AS INT.
DEF VAR vcfop AS CHAR.
DEF VAR ventsai AS CHAR.
DEF VAR vgrupooper AS CHAR.
DEF VAR vnovocfop AS CHAR.
DEF VAR vregimeTrib LIKE geralpessoas.regimeTrib.
DEF VAR vcrt LIKE geralpessoas.crt.
DEF VAR vidPessoaDestinatario AS INT.

//--DEF VAR arrayImposto AS CHAR EXTENT NO-UNDO.
DEF VAR arraynomeImposto AS CHAR EXTENT NO-UNDO.
DEF VAR arrayTotal AS CHAR EXTENT NO-UNDO.

DEF VAR iDet      AS INT.
DEF VAR iimposto AS INT.
DEF VAR inomeImposto AS INT.
DEF VAR itotal AS INT.

DEF VAR xmlentrada as longchar.

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field idNota  LIKE fisnota.idNota
    field idEmpresa  AS INT.

def temp-table ttsaida  no-undo SERIALIZE-NAME "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int SERIALIZE-NAME "status"
    field descricaoStatus      as CHAR.
    
def TEMP-TABLE ttentrada_imendes NO-UNDO serialize-name "dadosEntrada"  /* JSON ENTRADA */
    field idEmpresa      AS INT
    field idFornecimento      AS INT.

//---------- fisnota -------------    
def temp-table ttfisnota no-undo serialize-name "fisnota"   
    LIKE fisnota.
//---------- produtos -------------    
def temp-table ttprodutos no-undo serialize-name "produtos"   
    LIKE produtos.
//---------- geralprodutos -------------    
def temp-table ttgeralprodutos no-undo serialize-name "geralprodutos"   
    LIKE geralprodutos.   
//---------- geralfornecimento -------------    
def temp-table ttgeralfornecimento no-undo serialize-name "geralfornecimento"   
    LIKE geralfornecimento.     
//---------- fisnotatotal -------------    
def temp-table ttfisnotatotal no-undo serialize-name "fisnotatotal"   
    LIKE fisnotatotal.
//---------- fisnotaproduto -------------    
def temp-table ttfisnotaproduto no-undo serialize-name "fisnotaproduto"   
    LIKE fisnotaproduto.
//---------- ttfisnotaproduicms -------------    
def temp-table ttfisnotaproduicms no-undo serialize-name "fisnotaproduicms"   
    LIKE fisnotaproduicms.
//---------- ttfisnotaproduimposto -------------    
def temp-table ttfisnotaproduimposto no-undo serialize-name "fisnotaproduimposto"   
    LIKE fisnotaproduimposto.
    
//---------- geralpessoas -------------    
def temp-table ttgeralpessoas no-undo serialize-name "geralpessoas"   
    LIKE geralpessoas
    FIELD idEmpresa AS INT.
    
//---------- pessoas -------------    
def temp-table ttpessoas no-undo serialize-name "pessoas"   
    LIKE pessoas.

    
hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


FIND fisnota WHERE fisnota.idNota = ttentrada.idNota NO-LOCK NO-ERROR. 
IF NOT AVAIL fisnota 
THEN DO:
    RUN montasaida (400,"Todas NFEs processadas").
    RETURN.
END.
RUN LOG("Iniciando idNota=" + STRING(ttentrada.idNota)). 

//FIND pessoas WHERE pessoas.idpessoa = fisnota.idpessoaemitente NO-LOCK.
//FIND geralpessoas OF pessoas NO-LOCK.


fix-codepage(xmlentrada) = "UTF-8".
COPY-LOB fisnota.XML to xmlentrada.

omParser = new Progress.Json.ObjectModel.ObjectModelParser().
joEntrada = cast(omParser:Parse(xmlentrada), PROGRESS.Json.ObjectModel.JsonObject).

jonfeProc = joEntrada:GetJsonObject("nfeProc") NO-ERROR.
if type-of(jonfeProc, JsonObject) then do:
    joNFE = jonfeProc:GetJsonObject("NFe").
END. 
ELSE DO:
    joNFE = joEntrada:GetJsonObject("NFe").
END.

//joNFE = joEntrada:GetJsonObject("NFe").
joinfNFe = joNFE:GetJsonObject("infNFe"). 

joemit = joinfNFe:GetJsonObject("emit").
jodest = joinfNFe:GetJsonObject("dest").
RUN LOG("CNPJ da nota: " + joemit:GetCharacter("CNPJ")).
//---------- EMITENTE -------------
FIND pessoas WHERE pessoas.cpfCnpj = joemit:GetCharacter("CNPJ") NO-LOCK NO-ERROR.
IF AVAIL pessoas 
THEN DO:
    vidPessoaEmitente = pessoas.idPessoa.
    RUN LOG("CNPJ já cadastrado em PESSOAS ").
END.
ELSE DO:
RUN LOG("CNPJ não cadastrado em PESSOAS ").
    FIND geralpessoas WHERE geralpessoas.cpfCnpj = joemit:GetCharacter("CNPJ") NO-LOCK NO-ERROR.
    IF NOT AVAIL geralpessoas
    THEN DO:
        CREATE ttgeralpessoas.
        ttgeralpessoas.cpfCnpj = joemit:GetCharacter("CNPJ").
        ttgeralpessoas.tipoPessoa = "J".
        ttgeralpessoas.nomePessoa = joemit:GetCharacter("xNome").
        ttgeralpessoas.nomeFantasia = joemit:GetCharacter("xFant") NO-ERROR.    
        ttgeralpessoas.IE = joemit:GetCharacter("IE") NO-ERROR.
        ttgeralpessoas.municipio = joemit:GetJsonObject("enderEmit"):GetCharacter("xMun") NO-ERROR.
        ttgeralpessoas.codigoCidade = int(joemit:GetJsonObject("enderEmit"):GetCharacter("cMun")) NO-ERROR.
        ttgeralpessoas.codigoEstado = joemit:GetJsonObject("enderEmit"):GetCharacter("UF") NO-ERROR.
        ttgeralpessoas.pais = joemit:GetJsonObject("enderEmit"):GetCharacter("xPais") NO-ERROR.
        ttgeralpessoas.bairro = joemit:GetJsonObject("enderEmit"):GetCharacter("xBairro") NO-ERROR.
        ttgeralpessoas.endereco = joemit:GetJsonObject("enderEmit"):GetCharacter("xLgr") NO-ERROR.
        ttgeralpessoas.endNumero = int(joemit:GetJsonObject("enderEmit"):GetCharacter("nro")) NO-ERROR.
        ttgeralpessoas.CEP = joemit:GetJsonObject("enderEmit"):GetCharacter("CEP") NO-ERROR.
        ttgeralpessoas.telefone = joemit:GetJsonObject("enderEmit"):GetCharacter("fone") NO-ERROR.
        ttgeralpessoas.crt = int(joemit:GetCharacter("CRT")) NO-ERROR.
        vcrt = int(joemit:GetCharacter("CRT")) NO-ERROR.
        IF (vcrt = 3) 
        THEN DO:
           vregimeTrib = "LP". 
        END.
        ELSE DO:
           vregimeTrib = "SN". 
        END.
        ttgeralpessoas.regimeTrib   = vregimeTrib.
        ttgeralpessoas.regimeEspecial   = "N".
        ttgeralpessoas.idEmpresa = ttentrada.idEmpresa.
        
        RUN LOG("Cria GERALPESSOAS cpfCnpj " + ttgeralpessoas.cpfCnpj).
        RUN admin/database/geralpessoas.p (  INPUT "PUT", 
                                             input table ttgeralpessoas,
                                             INPUT vtmp,
                                             output vmensagem).
        DELETE ttgeralpessoas.
        if vmensagem <> ? then do:
            RUN montasaida (400,vmensagem).
            RETURN.
        end.      
    END.
    ELSE DO:
        RUN LOG("CNPJ já cadastrado em GERALPESSOAS ").
    END.
        
    CREATE ttpessoas.
    ttpessoas.cpfCnpj = joemit:GetCharacter("CNPJ").
    RUN LOG("Cria PESSOAS cpfCnpj " + ttpessoas.cpfCnpj).
    RUN cadastros/database/pessoas.p (INPUT "PUT", 
                                     input table ttpessoas,
                                     output vidPessoaEmitente,
                                     output vmensagem).
    DELETE ttpessoas.
    if vmensagem <> ? then do:
        RUN montasaida (400,vmensagem).
        RETURN.
    end. 
END.


//---------- DESTINATARIO -------------
FIND pessoas WHERE pessoas.cpfCnpj = jodest:GetCharacter("CNPJ") NO-LOCK NO-ERROR.
IF AVAIL pessoas 
THEN DO: 
    vidPessoaDestinatario = pessoas.idPessoa.
END.
ELSE DO:
    FIND geralpessoas WHERE geralpessoas.cpfCnpj = jodest:GetCharacter("CNPJ") NO-LOCK NO-ERROR.
    IF NOT AVAIL geralpessoas
    THEN DO:
        CREATE ttgeralpessoas.
        ttgeralpessoas.cpfCnpj      = jodest:GetCharacter("CNPJ").
        ttgeralpessoas.tipoPessoa   = "J".
        ttgeralpessoas.nomePessoa   = jodest:GetCharacter("xNome").
        ttgeralpessoas.nomeFantasia = jodest:GetCharacter("xFant") NO-ERROR.    
        ttgeralpessoas.IE           = jodest:GetCharacter("IE") NO-ERROR.
        ttgeralpessoas.municipio    = jodest:GetJsonObject("enderDest"):GetCharacter("xMun") NO-ERROR.
        ttgeralpessoas.codigoCidade = int(jodest:GetJsonObject("enderDest"):GetCharacter("cMun")) NO-ERROR.
        ttgeralpessoas.codigoEstado = jodest:GetJsonObject("enderDest"):GetCharacter("UF") NO-ERROR.
        ttgeralpessoas.pais         = jodest:GetJsonObject("enderDest"):GetCharacter("xPais") NO-ERROR.
        ttgeralpessoas.bairro       = jodest:GetJsonObject("enderDest"):GetCharacter("xBairro") NO-ERROR.
        ttgeralpessoas.endereco     = jodest:GetJsonObject("enderDest"):GetCharacter("xLgr") NO-ERROR.
        ttgeralpessoas.endNumero    = int(jodest:GetJsonObject("enderDest"):GetCharacter("nro")) NO-ERROR.
        ttgeralpessoas.CEP          = jodest:GetJsonObject("enderDest"):GetCharacter("CEP") NO-ERROR.
        ttgeralpessoas.telefone     = jodest:GetJsonObject("enderDest"):GetCharacter("fone") NO-ERROR.
        ttgeralpessoas.crt          = int(jodest:GetCharacter("CRT")) NO-ERROR.
        vcrt = int(joemit:GetCharacter("CRT")) NO-ERROR.
        IF (vcrt = 3) 
        THEN DO:
           vregimeTrib = "LP". 
        END.
        ELSE DO:
           vregimeTrib = "SN". 
        END.
        ttgeralpessoas.regimeTrib   = vregimeTrib.
        ttgeralpessoas.regimeEspecial   = "N".
        ttgeralpessoas.idEmpresa = ttentrada.idEmpresa.
        
        RUN LOG("Cria GERALPESSOAS DESTINATARIO cpfCnpj " + ttgeralpessoas.cpfCnpj).
        RUN admin/database/geralpessoas.p (INPUT "PUT", 
                                             input table ttgeralpessoas,
                                             INPUT vtmp,
                                             output vmensagem).
        DELETE ttgeralpessoas.
        if vmensagem <> ? then do:
            RUN montasaida (400,vmensagem).
            RETURN.
        end. 
    END. 
        
    CREATE ttpessoas.
    ttpessoas.cpfCnpj = jodest:GetCharacter("CNPJ").
    RUN cadastros/database/pessoas.p (INPUT "PUT", 
                                      input table ttpessoas,
                                      output vidPessoaDestinatario,
                                      output vmensagem).
    DELETE ttpessoas.
    if vmensagem <> ? then do:
        RUN montasaida (400,vmensagem).
        RETURN.
    end. 
END.


FIND pessoas WHERE pessoas.idpessoa = vidPessoaEmitente NO-LOCK.
FIND geralpessoas OF pessoas NO-LOCK.


//---------- FISNOTATOTAL -------------
jototal = joinfNFe:GetJsonObject("total").
arrayTotal = jototal:GetNames().
DO itotal = 1 TO EXTENT(arrayTotal):
    jonomeTotal = jototal:GetJsonObject(arrayTotal[itotal]).
    CREATE ttfisnotatotal.
    ttfisnotatotal.idNota       =  fisnota.idNota.
    ttfisnotatotal.nomeTotal    =  arrayTotal[itotal].
    ttfisnotatotal.vBC          =  decimal(jonomeTotal:GetCharacter("vBC")) NO-ERROR.
    ttfisnotatotal.vICMS        =  decimal(jonomeTotal:GetCharacter("vICMS")) NO-ERROR.
    ttfisnotatotal.vICMSDeson   =  decimal(jonomeTotal:GetCharacter("vICMSDeson")) NO-ERROR.
    ttfisnotatotal.vFCPUFDest   =  decimal(jonomeTotal:GetCharacter("vFCPUFDest")) NO-ERROR.
    ttfisnotatotal.vICMSUFRemet =  decimal(jonomeTotal:GetCharacter("vICMSUFRemet")) NO-ERROR.
    ttfisnotatotal.vFCP         =  decimal(jonomeTotal:GetCharacter("vFCP")) NO-ERROR.
    ttfisnotatotal.vBCST        =  decimal(jonomeTotal:GetCharacter("vBCST")) NO-ERROR.
    ttfisnotatotal.vST          =  decimal(jonomeTotal:GetCharacter("vST")) NO-ERROR.
    ttfisnotatotal.vFCPST       =  decimal(jonomeTotal:GetCharacter("vFCPST")) NO-ERROR.
    ttfisnotatotal.vFCPSTRet    =  decimal(jonomeTotal:GetCharacter("vFCPSTRet")) NO-ERROR.
    ttfisnotatotal.vProd        =  decimal(jonomeTotal:GetCharacter("vProd")) NO-ERROR.
    ttfisnotatotal.vFrete       =  decimal(jonomeTotal:GetCharacter("vFrete")) NO-ERROR.
    ttfisnotatotal.vSeg         =  decimal(jonomeTotal:GetCharacter("vSeg")) NO-ERROR.
    ttfisnotatotal.vDesc        =  decimal(jonomeTotal:GetCharacter("vDesc")) NO-ERROR.
    ttfisnotatotal.vII          =  decimal(jonomeTotal:GetCharacter("vII")) NO-ERROR.
    ttfisnotatotal.vIPI         =  decimal(jonomeTotal:GetCharacter("vIPI")) NO-ERROR.
    ttfisnotatotal.vIPIDevol    =  decimal(jonomeTotal:GetCharacter("vIPIDevol")) NO-ERROR.
    ttfisnotatotal.vPIS         =  decimal(jonomeTotal:GetCharacter("vPIS")) NO-ERROR.
    ttfisnotatotal.vOutro       =  decimal(jonomeTotal:GetCharacter("vOutro")) NO-ERROR.
    ttfisnotatotal.vCOFINS      =  decimal(jonomeTotal:GetCharacter("vCOFINS")) NO-ERROR.
    ttfisnotatotal.vNF          =  decimal(jonomeTotal:GetCharacter("vNF")) NO-ERROR.
    ttfisnotatotal.vTotTrib     =  decimal(jonomeTotal:GetCharacter("vTotTrib")) NO-ERROR.
    
    RUN impostos/database/fisnotatotal.p (INPUT "PUT", 
                                        input table ttfisnotatotal,
                                        output vmensagem). 
    DELETE ttfisnotatotal.
    if vmensagem <> ? then do:
        RUN montasaida (400,vmensagem).
        RETURN. 
    end.     
END. 

//---------- FISNOTA -------------
do on error undo:   
    find CURRENT Fisnota  EXCLUSIVE.
    fisnota.idStatusNota = 1.
    RUN LOG("Atualiza campos idPessoaEmitente e idPessoaDestinatario da Nota id=" + STRING(fisnota.idNota)).

    fisnota.idPessoaEmitente  =  vidPessoaEmitente.
    fisnota.idPessoaDestinatario  = vidPessoaDestinatario.
end.

//---------- VERIFICA ARRAY/OBJECT -------------    
jodet = joinfNFe:GetJsonObject("det") NO-ERROR.
if type-of(jodet, JsonObject) then do:
    RUN LOG("type-of OBJECT idNota=" + STRING(ttentrada.idNota)). 
    RUN jodet.
END. 
ELSE DO:

    jadet = joinfNFe:GetJsonArray("det") NO-ERROR.
    if type-of(jadet, JsonArray) then do:
        RUN LOG("type-of ARRAY idNota=" + STRING(ttentrada.idNota)). 
        DO iDet = 1 to jadet:length on error undo, NEXT:
            jodet = jadet:GetJsonObject(iDet).
            RUN LOG(" idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)). 
            RUN jodet.
        END. /* iDet */
    END. 
END.

//---------- IMENDES ------------- 
RUN LOG("----------- INICIO IMENDES -------------").
RUN LOG("IDEMPRESA --> " + STRING(ttentrada.idEmpresa) + " - CNPJ --> " + joemit:GetCharacter("CNPJ")).
  
FOR EACH geralfornecimento WHERE geralfornecimento.cnpj = joemit:GetCharacter("CNPJ") NO-LOCK:
    
    CREATE ttentrada_imendes.
    ttentrada_imendes.idEmpresa = ttentrada.idEmpresa.
    ttentrada_imendes.idFornecimento = geralfornecimento.idFornecimento.
    RUN LOG("chamou Imendes com idFornecimento: " + STRING(ttentrada_imendes.idFornecimento)).
    RUN impostos/database/imendes_saneamento.p (  input table ttentrada_imendes, 
                                                  INPUT vtmp,
                                                  output vmensagem).
    DELETE ttentrada_imendes.
END.
            
            
RUN montasaida (200,"").
RUN LOG("Finalizando idNota=" + STRING(ttentrada.idNota)). 
RUN LOG("-------- FIM FISNOTA PROCESSAR ---------"). 
    
procedure montasaida.
DEF INPUT PARAM tstatus AS INT.
DEF INPUT PARAM tdescricaoStatus AS CHAR.

create ttsaida.
ttsaida.tstatus = tstatus.
ttsaida.descricaoStatus = tdescricaoStatus.

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
RUN LOG(string(tstatus) + " mensagem=" + tdescricaoStatus).
END PROCEDURE.


PROCEDURE jodet.
    DEF VAR vean    AS INT64.
    DEF VAR vcprod  LIKE geralfornecimento.refprod.
    
    joprod = jodet:GetJsonObject("prod").
   
    //---------- PROCESSA PRODUTOS -------------
    RUN LOG("218 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
        + " " + "VAI PEGAR OBJECT prod"). 
    /*
    joprod:Write(lcJsonauxiliar).
    OUTPUT TO VALUE(vtmp + "/fisnota_processar_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " JSON -> " STRING(lcJsonauxiliar)
            SKIP.
    OUTPUT CLOSE.
    */

    RUN LOG("230 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
        + " " + "EAN=" + joprod:GetCharacter("cEAN")). 

    vean    = INT64(joprod:GetCharacter("cEAN")) NO-ERROR.
    if vean = 0 THEN do:
        vean = ?.
    end.
    if vean <> ? then do:
        IF NOT STRING(vean) BEGINS "789" then do:
            vean = ?.            
        end.
        RUN LOG("241 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
            + " " + "vEAN=" + STRING(vean)). 
        
    end.

    vcprod  = joprod:GetCharacter("cProd") NO-ERROR.
    if vcprod <> ? then do:
        RUN LOG("248 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
            + " " + "vcprod=" + STRING(vcprod)). 
    end.
    
    vidgeralproduto = ?.
    /* procura geralprodutos por ean */
    if vean <> ? 
    then do:
        FIND geralprodutos WHERE 
            geralprodutos.eanProduto = vean
                NO-LOCK NO-ERROR.
        if AVAIL geralprodutos 
        THEN DO:
            vidgeralproduto = geralprodutos.idgeralproduto.
        end.
        ELSE DO:
            RUN geralprodutos (vean, joprod:GetCharacter("xProd"), 
                                OUTPUT vidgeralproduto, OUTPUT vmensagem).
            if vmensagem <> ? then do:
                RUN montasaida (400,vmensagem).
                RETURN.
            end.             
        end.
    end.
    RUN LOG("281 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
    + " geralprodutos " + IF vidgeralproduto = ? THEN "?" ELSE string(vidgeralproduto)). 

    if vidgeralproduto = ?
    then do:
        /* Procura geralprodutos, por geralfornecimento com cProd*/
        if vcprod = ? 
        THEN DO:
            RUN LOG("289 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
            + "cprod eh null "). 
        END.
        if vcprod <> ?
        then do:
            FIND geralfornecimento WHERE
                geralfornecimento.cnpj =  geralpessoas.cpfcnpj AND
                geralfornecimento.refproduto = vcprod
                NO-LOCK NO-ERROR.
            RUN LOG("298x idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
                + " teste fornecimento " + string(geralpessoas.cpfcnpj) + "/" + vcprod + 
                " " + string(AVAIL geralfornecimento,"Avail geralfornecimento/Not avail geralfornecimento")). 
            RUN LOG("298xaa idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)).    
            IF AVAIL geralfornecimento
            THEN do:
                FIND geralprodutos WHERE geralprodutos.idgeralproduto =   geralfornecimento.idgeralproduto
                    NO-LOCK NO-ERROR.
               RUN LOG("298xa idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)).
               RUN LOG("298x idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
                + " teste geralprodutos " +  
                " " + string(AVAIL geralprodutos,"Avail geralprodutos/Not avail geralprodutos")). 
    
                if AVAIL geralprodutos 
                then do:
                    vidgeralproduto =    geralprodutos.idgeralproduto.
                end.
                /* helio 17/05/2024 - não cria mais geralproduto
                *ELSE DO:
                *    RUN geralprodutos (?, joprod:GetCharacter("xProd"), 
                *                        OUTPUT vidgeralproduto, OUTPUT vmensagem).
                *    if vmensagem <> ? then do:
                *        RUN montasaida (400,vmensagem).
                *        RETURN.
                *    end.             
                *END.
                */
            END.    
        end.
        
    end.
    RUN LOG("331 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
    + " geralprodutos " + (IF vidgeralproduto = ? THEN "?" ELSE string(vidgeralproduto))). 

    if vidgeralproduto <> ? 
    THEN DO:
        RUN LOG("336 ja tem idgeralproduto " + (IF vidgeralproduto = ? THEN "?" ELSE STRING(vidgeralproduto))).                      
    END.
    
    /* helio 17/05/2024 - não cria mais geralproduto sem ean
    *if vidgeralproduto = ? 
    *then do:
    *    RUN geralprodutos (?, joprod:GetCharacter("xProd"), 
    *                        OUTPUT vidgeralproduto, OUTPUT vmensagem).
    *    if vmensagem <> ? then do:
    *        RUN montasaida (400,vmensagem).
    *        RETURN.
    *    end.             
    *    FIND geralproduto WHERE geralproduto.idgeralproduto = vidgeralproduto NO-LOCK.
    *end.
    */
    
    if vidGeralProduto <> ? and /* Testa se EAN foi Usado para outro produto */
       vcprod <> ? 
    then do:
        FIND geralfornecimento WHERE
            geralfornecimento.cnpj =  geralpessoas.cpfcnpj AND
            geralfornecimento.idgeralproduto = vidGeralProduto
            NO-LOCK NO-ERROR.

        RUN LOG("360 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
            + " teste geralfornecimento " + string(geralpessoas.cpfcnpj) +  
            "/idproduto=" + string(vidGeralProduto) +
            " " + string(AVAIL geralfornecimento,"Avail/Not avail")).                      
        if avail geralfornecimento 
        then do:
            if geralfornecimento.refprod <> vcprod /* Outro Produto */
            then do:
                /* Criar novo geralproduto , SEM EAN */
                /* helio 17/05/2024 - não cria mais geralproduto sem ean
                *RUN geralprodutos (?, joprod:GetCharacter("xProd"), 
                *                OUTPUT vidgeralproduto, OUTPUT vmensagem).
                *if vmensagem <> ? then do:
                *    RUN montasaida (400,vmensagem).
                *    RETURN.
                *end.             
                *FIND geralproduto WHERE geralproduto.idgeralproduto = vidgeralproduto NO-LOCK.
                */
            end.
        end.

    end.
    RUN LOG("384 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
    + " vcprod " + string(vcprod)). 
    if vcprod <> ? AND vidGeralProduto <> ? /* helio 17/05/2024 - só se tem geralproduto*/
    then do:
        FIND geralfornecimento WHERE
            geralfornecimento.cnpj =  geralpessoas.cpfcnpj AND
            geralfornecimento.refproduto = vcprod
            NO-LOCK NO-ERROR.
        RUN LOG("393 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
            + " teste geralfornecimento " + string(geralpessoas.cpfcnpj) +  
            "/refproduto=" + string(vcprod) +
            " " + string(AVAIL geralfornecimento,"Avail/Not avail")).                 
        IF NOT AVAIL geralfornecimento
        THEN do:
            vcfop = joprod:GetCharacter("CFOP").
            ventsai = SUBSTRING(vcfop,1,1).
            if ventsai = "1" or ventsai = "2" or ventsai = "3"
                      then ventsai = "1".
                      else ventsai = "5".
            vgrupooper = SUBSTRING(vcfop,2,3).
            
            find fisoperacao where fisoperacao.idEntSai = int(ventsai) AND fisoperacao.idGrupoOper = int(vgrupooper)  no-lock no-error.
            IF avail fisoperacao then do:
                vnovocfop =  fisoperacao.cfopOposto.   
            end.
            ELSE DO:
                vnovocfop = joprod:GetCharacter("CFOP").
            END.
            
            CREATE ttgeralfornecimento.
            ttgeralfornecimento.Cnpj            =  geralpessoas.cpfcnpj.
            ttgeralfornecimento.nomeproduto     =  joprod:GetCharacter("xProd").
            ttgeralfornecimento.refProduto      =  vcprod.
            ttgeralfornecimento.idGeralProduto  =  vidGeralProduto.
            ttgeralfornecimento.valorCompra     =  decimal(joprod:GetCharacter("vUnCom")) NO-ERROR.
            ttgeralfornecimento.cfop            =  vnovocfop.
            
            vidfornecimento = ?.
            RUN admin/database/geralfornecimento.p (INPUT "PUT", 
                                            input table ttgeralfornecimento,
                                            output vidfornecimento,
                                            output vmensagem).  
            
            DELETE ttgeralfornecimento.
            //MESSAGE vmensagem VIEW-AS ALERT-BOX.
            if vmensagem <> ? then do:
                RUN montasaida (400,vmensagem).
                RETURN.
            end.          
        end.
        
    end.

    RUN LOG("446 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
    + " vai criar  produtos emitente = " + string(fisnota.idPessoaEmitente) +  
    "/refproduto=" + string(vcprod) + 
    "/idgeralproduto=" + (IF vidgeralproduto = ? THEN "?" ELSE string(vidgeralproduto)) ).  

    //---------- PRODUTO -------------   
    FIND produtos WHERE produtos.idgeralproduto     = vidGeralProduto AND
                        produtos.idPessoaFornecedor  = fisnota.idPessoaEmitente AND
                        produtos.refproduto         = vcprod
                NO-LOCK NO-ERROR.
    IF AVAIL produtos 
    then do:
        vidProduto = produtos.idproduto.
    END.
    ELSE DO:
        CREATE ttprodutos.
        ttprodutos.idGeralProduto   =  vidGeralProduto.
        ttprodutos.idPessoaFornecedor = fisnota.idPessoaEmitente.
        ttprodutos.refProduto       =  vcprod.
        ttprodutos.eanProduto       = vean.
        ttprodutos.nomeProduto      =  joprod:GetCharacter("xProd") NO-ERROR.
        ttprodutos.valorCompra      =  decimal(joprod:GetCharacter("vUnCom")) NO-ERROR.
        ttprodutos.codigoNcm        =  joprod:GetCharacter("NCM") NO-ERROR.
        ttprodutos.codigoCest       =  joprod:GetCharacter("CEST") NO-ERROR.
        
        RUN cadastros/database/produtos.p (INPUT "PUT", 
                                           input table ttprodutos,
                                           output vidProduto,
                                           output vmensagem).
        DELETE ttprodutos.
        //MESSAGE vmensagem VIEW-AS ALERT-BOX.
        if vmensagem <> ? then do:
            RUN montasaida (400,vmensagem).
            RETURN.
        end.   
    end.
                
    
    RUN LOG("446 idNota=" + STRING(ttentrada.idNota) + " iDET=" + STRING(idet)
    + " vai criar  fisnotaproduto").  
    
    //---------- FISNOTAPRODUTO -------------
    CREATE ttfisnotaproduto.
    ttfisnotaproduto.idNota         =  fisnota.idNota.
    ttfisnotaproduto.nItem          =  int(jodet:GetJsonObject("@attributes"):GetCharacter("nItem")).
    ttfisnotaproduto.idProduto      =  vidProduto.
    ttfisnotaproduto.quantidade     =  decimal(joprod:GetCharacter("qCom")) NO-ERROR.
    ttfisnotaproduto.unidCom        =  joprod:GetCharacter("uCom") NO-ERROR.
    ttfisnotaproduto.valorUnidade   =  decimal(joprod:GetCharacter("vUnCom")) NO-ERROR.
    ttfisnotaproduto.valorTotal     =  decimal(joprod:GetCharacter("vProd")) + decimal(joprod:GetCharacter("vOutro")) NO-ERROR.
    ttfisnotaproduto.cfop           =  joprod:GetCharacter("CFOP") NO-ERROR.
    
    
    RUN impostos/database/fisnotaproduto.p (INPUT "PUT", 
                                        input table ttfisnotaproduto,
                                        output vmensagem).
    DELETE ttfisnotaproduto.
    //MESSAGE vmensagem VIEW-AS ALERT-BOX.
    if vmensagem <> ? then do:
        RUN montasaida (400,vmensagem).
        RETURN.
    end.      
       
    //---------- PROCESSA IMPOSTOS -------------    
    joimposto = jodet:GetJsonObject("imposto").
    //--arrayImposto = joimposto:GetNames().

    //--DO iimposto = 1 TO EXTENT(arrayImposto):
    DEF VAR cimposto AS CHAR EXTENT 4 INITIAL ["ICMS","PIS","COFINS","IPI"].
    
    DO iimposto = 1 TO 4:
        
        //--IF arrayImposto[iimposto] = "ICMS"
        IF cimposto[iimposto] = "ICMS"
        THEN DO:
            //--joicms = joimposto:GetJsonObject(arrayImposto[iimposto]).
            joicms = joimposto:GetJsonObject(cimposto[iimposto]) NO-ERROR. //+
            if NOT type-of(joicms, JsonObject)
            THEN NEXT.
            arraynomeImposto = joicms:GetNames().
            DO inomeImposto = 1 TO EXTENT(arraynomeImposto):
                jonomeImposto = joicms:GetJsonObject(arraynomeImposto[inomeImposto]).
                
                //Atualiza origem de GeralFornecimento
                FIND geralfornecimento WHERE geralfornecimento.idfornecimento =  vidfornecimento EXCLUSIVE NO-ERROR.
                if AVAIL geralfornecimento then do:
                      geralfornecimento.origem = int(jonomeImposto:GetCharacter("orig")) NO-ERROR.
                end.
                
               
                
                //---------- FISNOTAPRODUICMS -------------
                CREATE ttfisnotaproduicms.
                ttfisnotaproduicms.idNota       = fisnota.idNota.
                ttfisnotaproduicms.nItem        = int(jodet:GetJsonObject("@attributes"):GetCharacter("nItem")).   
                //--ttfisnotaproduicms.imposto      = arrayImposto[iimposto].
                ttfisnotaproduicms.imposto      = cimposto[iimposto].
                ttfisnotaproduicms.nomeImposto  = arraynomeImposto[inomeImposto].
                ttfisnotaproduicms.vTotTrib     = int(joimposto:GetCharacter("vTotTrib")) NO-ERROR.
                ttfisnotaproduicms.orig         = int(jonomeImposto:GetCharacter("orig")) NO-ERROR.
                ttfisnotaproduicms.CSOSN        = jonomeImposto:GetCharacter("CSOSN") NO-ERROR.
                ttfisnotaproduicms.modBCST      = int(jonomeImposto:GetCharacter("modBCST")) NO-ERROR.
                ttfisnotaproduicms.pMVAST       = decimal(jonomeImposto:GetCharacter("pMVAST")) NO-ERROR.
                ttfisnotaproduicms.vBCST        = decimal(jonomeImposto:GetCharacter("vBCST")) NO-ERROR.
                ttfisnotaproduicms.pICMSST      = decimal(jonomeImposto:GetCharacter("pICMSST")) NO-ERROR.
                ttfisnotaproduicms.vICMSST      = decimal(jonomeImposto:GetCharacter("vICMSST")) NO-ERROR.
                ttfisnotaproduicms.CST          = jonomeImposto:GetCharacter("CST") NO-ERROR.
                ttfisnotaproduicms.modBC        = jonomeImposto:GetCharacter("modBC") NO-ERROR.
                ttfisnotaproduicms.vBC          = decimal(jonomeImposto:GetCharacter("vBC")) NO-ERROR.
                IF  ttfisnotaproduicms.vBC = ? THEN ttfisnotaproduicms.vBC = 0.
                
                ttfisnotaproduicms.pICMS        = decimal(jonomeImposto:GetCharacter("pICMS")) NO-ERROR.
                IF  ttfisnotaproduicms.pICMS = ? THEN ttfisnotaproduicms.pICMS = 0.
                
                ttfisnotaproduicms.vICMS        = decimal(jonomeImposto:GetCharacter("vICMS")) NO-ERROR. 
                IF  ttfisnotaproduicms.vICMS = ? THEN ttfisnotaproduicms.vICMS = 0.
                    
                RUN impostos/database/fisnotaproduicms.p (INPUT "PUT", 
                                                    input table ttfisnotaproduicms,
                                                    output vmensagem). 
                DELETE ttfisnotaproduicms.
                //MESSAGE vmensagem VIEW-AS ALERT-BOX.
                if vmensagem <> ? then do:
                    RUN montasaida (400,vmensagem).
                    RETURN.
                end.   
            END. 
        END.
        IF cimposto[iimposto] = "PIS" OR cimposto[iimposto] = "COFINS" //--OR cimposto[iimposto] = "IPI"
        THEN DO:
        //--IF arrayImposto[iimposto] = "PIS" OR arrayImposto[iimposto] = "COFINS" OR arrayImposto[iimposto] = "IPI"
            
            //provisório
            //MESSAGE arrayImposto[iimposto] view-as alert-box.
            joImpostos = joimposto:GetJsonObject(cimposto[iimposto]) NO-ERROR.
            IF NOT type-of(joImpostos, JsonObject)
            THEN NEXT.
            arraynomeImposto = joImpostos:GetNames().
            //MESSAGE EXTENT(arraynomeImposto) view-as alert-box.

            DO inomeImposto = 1 TO EXTENT(arraynomeImposto):
                jonomeImposto = joImpostos:GetJsonObject(arraynomeImposto[inomeImposto]).
                
                vpercentual = "p" + cimposto[iimposto].
                vvalor = "v" + cimposto[iimposto].
                
                
                //---------- FISNOTAPRODUIMPOSTO -------------
                CREATE ttfisnotaproduimposto.
                ttfisnotaproduimposto.idNota        = fisnota.idNota.
                ttfisnotaproduimposto.nItem         = int(jodet:GetJsonObject("@attributes"):GetCharacter("nItem")).
                ttfisnotaproduimposto.imposto       = cimposto[iimposto].
                ttfisnotaproduimposto.nomeImposto   = arraynomeImposto[inomeImposto].
                ttfisnotaproduimposto.cEnq          = decimal(joimposto:GetJsonObject(cimposto[iimposto]):GetCharacter("cEnq")) NO-ERROR.
                ttfisnotaproduimposto.CST           = jonomeImposto:GetCharacter("CST") NO-ERROR.
                ttfisnotaproduimposto.vBC           = decimal(jonomeImposto:GetCharacter("vBC")) NO-ERROR.
                IF  ttfisnotaproduimposto.vBC = ? THEN ttfisnotaproduimposto.vBC = 0.
                  
                ttfisnotaproduimposto.percentual    = decimal(joImpostos:GetCharacter(vpercentual)) NO-ERROR.
                IF  ttfisnotaproduimposto.percentual = ? THEN ttfisnotaproduimposto.percentual = 0.
                
                ttfisnotaproduimposto.valor         = decimal(joImpostos:GetCharacter(vvalor)) NO-ERROR. 
                IF  ttfisnotaproduimposto.valor = ? THEN ttfisnotaproduimposto.valor = 0.
                 
                RUN impostos/database/fisnotaproduimposto.p (INPUT "PUT", 
                                                    input table ttfisnotaproduimposto,
                                                    output vmensagem).   
                DELETE ttfisnotaproduimposto.
                //MESSAGE vmensagem VIEW-AS ALERT-BOX.
                if vmensagem <> ? then do:
                    RUN montasaida (400,vmensagem).
                    RETURN.
                end.  
            END.  
        END.   
        
    END.    
    RUN LOG("605 FIM PEGAR OBJECT prod"). 
      
END PROCEDURE.



procedure geralprodutos:
    DEF INPUT PARAM pean AS INT64.
    DEF INPUT PARAM pprod AS CHAR.
    DEF OUTPUT PARAM pidGeralProduto AS INT64.
    DEF OUTPUT PARAM pmensagem AS CHAR.
    
            CREATE ttgeralprodutos.
            ttgeralprodutos.eanProduto    =  pean.
            ttgeralprodutos.nomeProduto   =  pprod.
            
            RUN admin/database/geralprodutos.p (INPUT "PUT", 
                                                   input table ttgeralprodutos,
                                                   OUTPUT pidGeralProduto,
                                                   OUTPUT pmensagem). 
            DELETE ttgeralprodutos.
            
END PROCEDURE.

procedure geralfornecimento:    
    DEF INPUT PARAM vidGeralProduto AS INT64.
    DEF INPUT PARAM cProd AS CHAR.
    DEF OUTPUT PARAM vmensagem AS CHAR.
            
            CREATE ttgeralfornecimento.
            ttgeralfornecimento.Cnpj    =  geralpessoas.cpfcnpj.
            ttgeralfornecimento.refProduto   =  joprod:GetCharacter("cProd") NO-ERROR.
            ttgeralfornecimento.idGeralProduto   =  vidGeralProduto.
            ttgeralfornecimento.valorCompra   =  decimal(joprod:GetCharacter("vUnCom")) NO-ERROR.
            ttgeralfornecimento.origem        =  int(jonomeImposto:GetCharacter("orig")) NO-ERROR.
            ttgeralfornecimento.cfop          =  joprod:GetCharacter("CFOP") NO-ERROR.
            
            RUN admin/database/geralfornecimento.p (INPUT "PUT", 
                                            input table ttgeralfornecimento,
                                            output vmensagem). 
            DELETE ttgeralfornecimento.


END PROCEDURE.

procedure LOG.
    DEF INPUT PARAM vmensagem AS CHAR.    
    OUTPUT TO VALUE(vtmp + "/fisnota_processar_" + string(today,"99999999") + ".log") APPEND.
        PUT UNFORMATTED 
            STRING (TIME,"HH:MM:SS")
            " progress -> " vmensagem
            SKIP.
    OUTPUT CLOSE.
    
END PROCEDURE.










