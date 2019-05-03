<?php

include_once '../class/padrao.php';

$clientes = new Clientes();
$clientes->editCliente($_nome = 'tiag3', '1', 3, 30);
$consulta = $clientes->listClientes(0, 't', 't');
foreach ($consulta as $value) {
    echo 'Codigo: ' . $value['codigo'] . ' | ';
    echo 'Nome: ' . $value['nome'] . ' | ';
    echo 'Grupo: ' . $value['saldo_cliente'] . ' | ';
    echo 'Codigo Grupo: ' . $value['home_usuario_codigo'] . '<br/>';
}

$vendas = new Vendas();

$vendas->addProdVenda('2', '1', '4');
$vendas->addProdVenda('2', '2', '2');
$vendas->addProdVenda('2', '28', '3');
$vendas->addProdVenda('3', '1', '5');
$vendas->addProdVenda('3', '2', '5');
$vendas->addProdVenda('3', '28', '3');
$vendas->addProdVenda('4', '15', '1');
$vendas->addProdVenda('4', '22', '0.402');
$vendas->addProdVenda('4', '15', '2');
$vendas->addProdVenda('4', '22', '0.609');

$vendas->editVenda('1', 0);
$vendas->editVenda('2', 3);
$vendas->editVenda('3', 1);
$vendas->editVenda('4', 0);

$lista_prod_venda = $vendas->listProdVenda(2);
$lista_prod_venda1 = $vendas->listProdVenda(1);
$lista_prod_venda2 = $vendas->listProdVenda(4);

$consulta1 = $vendas->listVendas();
foreach ($consulta1 as $value) {
    echo 'Codigo: ' . $value['codigo'] . ' | ';
    echo 'Data Venda: ' . $value['data_venda'] . ' | ';
    echo 'Usuario: ' . $value['nome_usuario'] . ' | ';
    echo 'Cliente: ' . $value['nome_cliente'] . ' | ';
    echo 'Valor Total: ' . $value['valor_total'] . ' | ';
    echo 'Valor Desconto: ' . $value['valor_desconto'] . ' | ';
    echo 'Valor Final: ' . $value['valor_final'] . '<br/>';
}

$produto = new Produto();
$lista_prod = $produto->listProdutos(0, '', 'b');
foreach ($lista_prod as $value) {
    echo 'Codigo: ' . $value['codigo'] . ' | ';
    echo 'Grupo Codigo: ' . $value['grupo_produto_codigo'] . ' | ';
    echo 'Nome: ' . $value['nome'] . ' | ';
    echo 'Valor Venda: ' . $value['valor_venda_padrao'] . ' | ';
    echo 'Usuario: ' . $value['home_usuario_codigo'] . ' | ';
    echo 'Qtd Estoque: ' . $value['qtd_estoque'] . ' | ';
    echo 'Unidade: ' . $value['unidade'] . ' | ';
    echo 'Grupo: ' . $value['grupo_descricao'] . '<br/>';
}

foreach ($lista_prod_venda as $value) {
    echo 'Codigo: ' . $value['vendas_venda_codigo'] . ' | ';
    echo 'Grupo Codigo: ' . $value['vendas_produto_codigo'] . ' | ';
    echo 'Nome: ' . $value['nome_produto'] . ' | ';
    echo 'Valor Venda: ' . $value['produto_valor'] . ' | ';
    echo 'Usuario: ' . $value['quantidade'] . ' | ';
    echo 'Grupo: ' . $value['preco_unidade'] . '<br/>';
}

foreach ($lista_prod_venda1 as $value) {
    echo 'Codigo: ' . $value['vendas_venda_codigo'] . ' | ';
    echo 'Grupo Codigo: ' . $value['vendas_produto_codigo'] . ' | ';
    echo 'Nome: ' . $value['nome_produto'] . ' | ';
    echo 'Valor Venda: ' . $value['produto_valor'] . ' | ';
    echo 'Usuario: ' . $value['quantidade'] . ' | ';
    echo 'Grupo: ' . $value['preco_unidade'] . '<br/>';
}

foreach ($lista_prod_venda2 as $value) {
    echo 'Codigo: ' . $value['vendas_venda_codigo'] . ' | ';
    echo 'Grupo Codigo: ' . $value['vendas_produto_codigo'] . ' | ';
    echo 'Nome: ' . $value['nome_produto'] . ' | ';
    echo 'Valor Venda: ' . $value['produto_valor'] . ' | ';
    echo 'Usuario: ' . $value['quantidade'] . ' | ';
    echo 'Grupo: ' . $value['preco_unidade'] . '<br/>';
}

$lista_forma_paga = $vendas->listFormaPagamento();
foreach ($lista_forma_paga as $value) {
    echo 'Codigo: ' . $value['codigo'] . ' | ';
    echo 'Nome: ' . $value['nome'] . ' | ';
    echo 'taxa: ' . $value['taxa'] . ' | ';
    echo 'descricao: ' . $value['descricao'] . '<br/>';
}

$financeiro = new Financeiro();

echo 'abdabdabdadb ' . $financeiro->editLancamentoCaixa(1, 2, 150.01, 12) . '<br/>';

$list_tipo_lancamento = $financeiro->listTipoLancamento();

foreach ($list_tipo_lancamento as $value) {
    echo 'Codigo: ' . $value['codigo'] . ' | ';
    echo 'Nome: ' . $value['descricao'] . ' | ';
    echo 'descricao: ' . $value['sinal'] . '<br/>';
}

$list_caixa = $financeiro->listCaixas();

foreach ($list_caixa as $value) {
    echo 'Codigo: ' . $value['codigo'] . ' | ';
    echo 'Nome: ' . $value['descricao'] . ' | ';
    echo 'descricao: ' . $value['data_caixa'] . ' | ';
    echo 'descricao: ' . $value['situacao_caixa'] . ' | ';
    echo 'descricao: ' . $value['usuario_codigo'] . '<br/>';
}

$res_caixa_1 = $financeiro->getResumoCaixa(1);
foreach ($res_caixa_1 as $value) {
    echo 'Codigo Caixa: ' . $value['caixa_codigo'] . ' | ';
    echo 'Valor Final: ' . $value['valor_final'] . ' | ';    
    echo 'Quantidade Lancamentos: ' . $value['qtd_lancamento'] . '<br/>';
}

$lista_lancamento = $financeiro->listLancamentosCaixas();
foreach ($lista_lancamento as $value) {
    echo 'Codigo Caixa: ' . $value['caixa_codigo'] . ' | ';
    echo 'Valor Final: ' . $value['valor'] . ' | ';    
    echo 'Quantidade Lancamentos: ' . $value['lancamento_descricao'] . '<br/>';
}

