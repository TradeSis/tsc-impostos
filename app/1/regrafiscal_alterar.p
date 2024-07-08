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
    field descricaoStatus      as char.

DEF BUFFER bfiscalregra FOR fiscalregra.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.


if not avail ttentrada
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Dados de Entrada nao encontrados".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

if ttentrada.idRegra = ?
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Dados de Entrada Invalidos".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

find fiscalregra where fiscalregra.idRegra = ttentrada.idRegra no-lock no-error.
if not avail fiscalregra
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Regra nao cadastrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

IF ttentrada.codRegra <> ? AND ttentrada.codRegra <> fiscalregra.codRegra
THEN DO:
    find bfiscalregra where bfiscalregra.codRegra = ttentrada.codRegra no-lock no-error.
    IF avail bfiscalregra
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "Regra já cadastrado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.    
    
END.

IF ttentrada.codExcecao <> ? AND ttentrada.codExcecao <> fiscalregra.codExcecao
THEN DO:
    find bfiscalregra where bfiscalregra.codExcecao = ttentrada.codExcecao no-lock no-error.
    IF avail bfiscalregra
    then do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "Regra já cadastrado".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        message string(vlcSaida).
        return.
    end.    
    
END.


do on error undo:
    find fiscalregra where fiscalregra.idRegra = ttentrada.idRegra exclusive no-error.
    BUFFER-COPY ttentrada 
            EXCEPT idRegra codRegra codExcecao 
            TO fiscalregra.
    IF ttentrada.codRegra <> ? 
    THEN DO:
        fiscalregra.codRegra = ttentrada.codRegra.
    END.
    IF ttentrada.codExcecao <> ? 
    THEN DO:
        fiscalregra.codExcecao = ttentrada.codExcecao.
    END.
end.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "Regra Fiscal alterada com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
