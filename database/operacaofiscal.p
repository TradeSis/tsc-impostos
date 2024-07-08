
// Programa especializado em CRAR a tabela fiscaloperacao
def temp-table ttentrada no-undo serialize-name "fiscaloperacao"   /* JSON ENTRADA */
    LIKE fiscaloperacao.

DEF INPUT PARAM vacao AS CHAR.   
DEF INPUT PARAM TABLE FOR ttentrada.
def output param vidoperacaofiscal LIKE fiscaloperacao.idoperacaofiscal.
def output param vmensagem as char.

vmensagem = ?.
vidoperacaofiscal = ?.

find first ttentrada no-error.
if not avail ttentrada then do:
    vmensagem = "Dados de Entrada fiscaloperacao nao encontrados".
    return.
end.

if ttentrada.idGrupo = ? OR ttentrada.codigoEstado = ? OR ttentrada.cFOP = ? OR ttentrada.codigoCaracTrib = ? OR ttentrada.finalidade = ?
then do:
    vmensagem = "Dados de Entrada Invalidos".
    return.
end.

find fiscaloperacao where 
        fiscaloperacao.idGrupo = ttentrada.idGrupo AND
        fiscaloperacao.codigoEstado = ttentrada.codigoEstado AND
        fiscaloperacao.cFOP = ttentrada.cFOP AND
        fiscaloperacao.codigoCaracTrib = ttentrada.codigoCaracTrib AND
        fiscaloperacao.finalidade = ttentrada.finalidade
    no-lock no-error.

if avail fiscaloperacao
then do:
    vmensagem = "Operacao ja cadastrada".
    return.
end.

IF vacao = "PUT"
THEN DO:
    do on error undo:
        create fiscaloperacao.
        vidoperacaofiscal = fiscaloperacao.idoperacaofiscal.
        BUFFER-COPY ttentrada EXCEPT idoperacaofiscal TO fiscaloperacao.
    end.  
END.

