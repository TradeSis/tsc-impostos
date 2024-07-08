
// Programa especializado em CRAR a tabela fisnatureza
def temp-table ttentrada no-undo serialize-name "fisnatureza"   /* JSON ENTRADA */
    LIKE fisnatureza.

  
def input param vAcao as char.
DEF INPUT PARAM TABLE FOR ttentrada.
def output param vmensagem as char.

vmensagem = ?.

find first ttentrada no-error.
if not avail ttentrada then do:
    vmensagem = "Dados de Entrada nao encontrados".
    return.    
end.

if ttentrada.nomeNatureza = ""
then do:
    vmensagem = "Dados de Entrada Invalidos".
    return.    
end.

if vAcao = "PUT"
THEN DO:
    /*find fisnatureza where fisnatureza.nomeNatureza = ttentrada.nomeNatureza 
            no-lock no-error.
    if avail fisnatureza
    then do:
        vmensagem = "Natureza ja cadastrada".
        return.  
    end. */


    do on error undo:
        create fisnatureza.
        BUFFER-COPY ttentrada EXCEPT idNatureza TO fisnatureza .
    end.

END.