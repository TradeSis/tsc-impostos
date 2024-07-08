def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "fisnota"   /* JSON ENTRADA */
    field chaveNFe                  like fisnota.chaveNFe
    field naturezaOp                like fisnota.naturezaOp
    field modelo                    like fisnota.modelo
    field XML                       like fisnota.XML
    field serie                     like fisnota.serie
    field NF                        like fisnota.NF
    field dtEmissao                 like fisnota.dtEmissao
    field idPessoaEmitente          like fisnota.idPessoaEmitente
    field idPessoaDestinatario      like fisnota.idPessoaDestinatario
    field idStatusNota              like fisnota.idStatusNota
    field vNF                       like fisnota.vNF
    field vProd                     like fisnota.vProd
    field vFrete                    like fisnota.vFrete
    field vSeg                      like fisnota.vSeg
    field vDesc                     like fisnota.vDesc
    field vOutro                    like fisnota.vOutro.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.


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

if ttentrada.chaveNFe = ?
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Dados de Entrada Invalidos".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

find fisnota where fisnota.chaveNFe = ttentrada.chaveNFe no-lock no-error.
if avail fisnota
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "NFE ja cadastrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.


do on error undo:
    create fisnota.
    BUFFER-COPY ttentrada TO fisnota .
end.

create ttsaida.
ttsaida.tstatus = 200.
ttsaida.descricaoStatus = "NFE cadastrada com sucesso".

hsaida  = temp-table ttsaida:handle.

lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
