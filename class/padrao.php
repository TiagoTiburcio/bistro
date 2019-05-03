<?php

include_once '../class/database.php';

/**
 * Description of Fornecedores
 *
 * @author tiagoc
 */
class Fornecedores extends Database {

    function listFornecedores() {
        $consulta = "  ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

}

/**
 * Description of Clientes
 *
 * Tabela Clientes possui 
 * `codigo` = codigo cliente
 * `nome` = nome clientes 
 * `data_criacao` = data criacao cliente 
 * `home_usuario_codigo` = usuario realizou ultima edição
 * `saldo_cliente` = saldo atual conta cliente
 * 
 * @author tiagoc
 */
class Clientes extends Database {

    /**
     * @param type $_codigo = 0 para todos
     * @param type $_nome = '' vazio para todos
     * @param type $_data_criacao = NULL para 
     * @param type $_saldo_cliente = t para todos
     * @return type
     */
    function listClientes($_codigo = 0, $_nome = '', $_saldo_cliente = 't') {
        if (($_codigo == 0 ) && ($_nome == '') && ($_saldo_cliente == 't')) {
            $filtro = '';
        } else {
            $filtro = $this->filtroListClientes($_codigo, $_nome, $_saldo_cliente);
        }
        $consulta = " SELECT `codigo`, `nome`, `data_criacao`, `home_usuario_codigo`, `saldo_cliente` FROM `vendas_cliente` $filtro ;";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

    private function filtroListClientes($_codigo = 0, $_nome = '', $_saldo_cliente = 't') {
        $filtro = 'where ';
        $cont = 0;
        if ($_codigo != 0) {
            $filtro = $filtro . ' `codigo` = ' . $_codigo;
            $cont = 1;
        }
        if (($_nome != '') && ($cont == 0)) {
            $filtro = $filtro . " `nome` like '%" . $_nome . "%'";
            $cont = 1;
        } elseif (($_nome != '') && ($cont != 0)) {
            $filtro = $filtro . " and `nome` like '%" . $_nome . "%'";
        }
        if (($_saldo_cliente != 't') && ($cont == 0)) {
            $filtro = $filtro . ' `saldo_cliente` = ' . $_saldo_cliente;
        } elseif (($_saldo_cliente != 't') && ($cont != 0)) {
            $filtro = $filtro . ' and `saldo_cliente` = ' . $_saldo_cliente;
        }
        return $filtro;
    }

    function ifExistCliente($_codigo = 0) {
        $consulta = " SELECT count(`codigo`) as cont, `codigo`, `nome`, `data_criacao`, `home_usuario_codigo`, `saldo_cliente` FROM bistro.vendas_cliente where codigo = $_codigo; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        foreach ($resultado as $value) {
            $retorno = $value['cont'];
        }
        if ($retorno == 1) {
            return true;
        } else {
            return false;
        }
    }

    function editCliente($_nome = '', $_codigo_usuario = 1, $_codigo = 'N', $_saldo_atual = '0') {
        date_default_timezone_set("America/Bahia");
        $data = date('Y-m-d H:i:s');
        if ($this->ifExistCliente($_codigo)) {
            $consulta = " UPDATE `vendas_cliente` SET `nome` = '$_nome', `home_usuario_codigo` = '$_codigo_usuario', `saldo_cliente` = '$_saldo_atual' WHERE `codigo` = '$_codigo'; ";
            $resultado = mysqli_query($this->connect(), $consulta);
        } else {
            $consulta = " INSERT INTO `vendas_cliente` (`nome`, `data_criacao`, `home_usuario_codigo`) VALUES ('" . $_nome . "','" . $data . "','" . $_codigo_usuario . "'); ";
            $resultado = mysqli_query($this->connect(), $consulta);
        }
    }

}

class Vendas extends Database {
    
    function listVendas($_codigo_cliente = 0, $_nome_cliente = '', $_codigo_usuario = 0, $_codigo_venda = 0) {
        if (($_codigo_cliente == 0) && ($_nome_cliente == '') && ($_codigo_usuario == 0)) {
            $filtro = '';
        } else {
            $filtro = $this->filtroListVendas($_codigo_cliente, $_nome_cliente, $_codigo_usuario, $_codigo_venda);
        }

        $consulta = " SELECT v.`codigo`, v.`data_venda`, v.`usuario_codigo`, v.`cliente_codigo`, v.situacao, v.`valor_total`, v.`valor_desconto`, (v.`valor_total` - v.`valor_desconto`) as valor_final , u.nome as nome_usuario, c.nome as nome_cliente FROM `vendas_venda` as v join `home_usuario` as u on u.codigo = v.usuario_codigo join `vendas_cliente` as c on c.codigo = v.cliente_codigo $filtro ; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

    private function filtroListVendas($_codigo_cliente = 0, $_nome_cliente = '', $_codigo_usuario = 0, $_codigo_venda = 0) {
        $filtro = 'where ';
        $cont = 0;
        if ($_codigo_cliente != 0) {
            $filtro = $filtro . ' `codigo_cliente` = ' . $_codigo_cliente;
            $cont = 1;
        }
        if (($_nome_cliente != '') && ($cont == 0)) {
            $filtro = $filtro . " `nome_cliente` like '%" . $_nome_cliente . "%'";
            $cont = 1;
        } elseif (($_nome_cliente != '') && ($cont != 0)) {
            $filtro = $filtro . " and `nome_cliente` like '%" . $_nome_cliente . "%'";
        }
        if (($_codigo_usuario != 0) && ($cont == 0)) {
            $filtro = $filtro . ' `usuario_codigo` = ' . $_codigo_usuario;
        } elseif (($_codigo_usuario != 0) && ($cont != 0)) {
            $filtro = $filtro . ' and `usuario_codigo` = ' . $_codigo_usuario;
        }
        if (($_codigo_venda != 0) && ($cont == 0)) {
            $filtro = $filtro . ' `usuario_codigo` = ' . $_codigo_venda;
        } elseif (($_codigo_venda != 0) && ($cont != 0)) {
            $filtro = $filtro . ' and `usuario_codigo` = ' . $_codigo_venda;
        }
        return $filtro;
    }

    function ifExistVenda($_codigo) {
        $consulta = " SELECT count(`codigo`) as cont FROM `vendas_venda` where `codigo` = '$_codigo' ; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        foreach ($resultado as $value) {
            $retorno = $value['cont'];
        }
        if ($retorno == 1) {
            return true;
        } else {
            return false;
        }
    }

    function editVenda($_codigo_venda = 'N', $_valor_desconto = 0, $_codigo_cliente = 0, $_codigo_usuario = 0, $_data_venda = '') {
        if ($_data_venda == '') {
            date_default_timezone_set("America/Bahia");
            $_data_venda = date('Y-m-d H:i:s');
        }
        if ($this->ifExistVenda($_codigo_venda)) {
            $resultado1 = $this->atuVenda($_codigo_venda);
            foreach ($resultado1 as $value) {
                $qtd_produtos = $value['qtd_produtos'];
                $total_venda = $value['total_venda'];
                $_codigo_cliente = $value['cliente_codigo'];
                $_codigo_usuario = $value['usuario_codigo'];
                if (($value['situacao'] != 'F')&& ($value['total_venda'] == $value['valor_pago'])){
                    $situacao_venda = 'F';
                } elseif (($value['situacao'] != 'F') && ($value['total_venda'] != $value['valor_pago'])) {
                    $situacao_venda = 'A';
                }
            }
            $consulta = " UPDATE `vendas_venda` SET `data_venda` = '$_data_venda', `usuario_codigo` = '$_codigo_usuario', `cliente_codigo` = '$_codigo_cliente', `valor_total` = '$total_venda', `valor_desconto` = '$_valor_desconto', `qtd_produtos_venda` = '$qtd_produtos' WHERE `codigo` = '$_codigo_venda'; ";
            $resultado = mysqli_query($this->connect(), $consulta);
        } elseif (($_codigo_cliente != 0) && ($_codigo_usuario != 0)) {
            $consulta = " INSERT INTO `vendas_venda` (`data_venda`, `usuario_codigo`, `cliente_codigo`) VALUES ('$_data_venda','$_codigo_usuario','$_codigo_cliente'); ";
            $resultado = mysqli_query($this->connect(), $consulta);
        } else {
            echo 'Sem Alteração na Venda ';
        }
    }
    /**
     * @param type $_codigo
     * @return type cliente_codigo | usuario_codigo | v.situacao | qtd_produtos | total_venda | valor_pago | qtd_lancamentos
     */
    private function atuVenda($_codigo) {
        $consulta = " SELECT v.cliente_codigo, v.usuario_codigo, v.situacao, COUNT(vp.vendas_produto_codigo) AS qtd_produtos, SUM(vp.produto_valor) AS total_venda,  c1.valor_pago, qtd_lancamentos FROM vendas_venda_produto AS vp JOIN vendas_venda AS v ON v.codigo = vp.vendas_venda_codigo join (select vendas_venda_codigo , count(lancameto_codigo) as qtd_lancamentos , sum(valor) as valor_pago from vendas_pagamento where vendas_venda_codigo = '$_codigo') as c1  on c1.vendas_venda_codigo = v.`codigo` WHERE vp.vendas_venda_codigo = '$_codigo' ; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

    function addProdVenda($_codigo_venda, $_codigo_produto, $_qtd_prod, $_codigo_usuario = 1) {
        date_default_timezone_set("America/Bahia");
        $_data_venda = date('Y-m-d H:i:s');
        if (($this->ifExistVenda($_codigo_venda)) && (!(is_nan($_qtd_prod)))) {
            $produto = new Produto();
            $prod_venda = $produto->getProduto($_codigo_produto);
            foreach ($prod_venda as $value) {
                $valor_total = round(($value['valor_venda_padrao'] * $_qtd_prod), 2);
                if ($value['cont'] != 0) {
                    $consulta1 = "delete FROM vendas_venda_produto where vendas_venda_codigo = '$_codigo_venda' and vendas_produto_codigo = '$_codigo_produto';";
                    $resultado1 = mysqli_query($this->connect(), $consulta1);
                    $consulta = " INSERT INTO `vendas_venda_produto` (`vendas_venda_codigo`, `vendas_produto_codigo`, `produto_valor`, `quantidade`, `preco_unidade`, `data_hora_inc`, `home_usuario_codigo`) VALUES ('$_codigo_venda', '$_codigo_produto', '$valor_total', '$_qtd_prod', '" . $value['valor_venda_padrao'] . "', '$_data_venda', '$_codigo_usuario'); ";
                    $resultado = mysqli_query($this->connect(), $consulta);
                }
            }
        }
    }

    function listProdVenda($_codigo_venda = '0', $_codigo_produto = '0', $_nome_produto = '') {
        if (($_codigo_venda != '0') && ($this->ifExistVenda($_codigo_venda))) {
            $filtro = " and vp.vendas_venda_codigo = '$_codigo_venda' ";
        } else {
            $filtro = "";
        }
        if ($_codigo_produto != '0') {
            $filtro = $filtro . " and vp.vendas_produto_codigo = '$_codigo_produto' ";
        }
        $consulta = " select vp.vendas_venda_codigo, vp.vendas_produto_codigo, p.nome as nome_produto, vp.produto_valor, vp.quantidade, vp.preco_unidade from vendas_produto as p join vendas_venda_produto as vp on p.codigo = vp.vendas_produto_codigo where  p.nome like '%$_nome_produto%' $filtro ; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

    function addPagamento($_codigo_venda, $_codigo_forma_pagamento, $_valor, $_codigo_caixa, $_codigo_lancamento = 0) {
        $financeiro = new Financeiro();
        $dados_caixa = $financeiro->listCaixas('', '', $_codigo_caixa);
        foreach ($dados_caixa as $value) {            
            if (($this->ifExistVenda($_codigo_venda)) && ($this->ifExistVenda($_codigo_forma_pagamento)) && ($_codigo_lancamento == 0) && ($value['situacao_caixa'] == 'Aberto')) {
                $consulta = " INSERT INTO `vendas_pagamento` (`vendas_forma_pagamento_codigo`, `vendas_venda_codigo`, `valor`, `caixa_codigo`, `lancameto_codigo`) VALUES ('$_codigo_forma_pagamento', '$_codigo_venda', '$_valor', '$_codigo_caixa', '" . $financeiro->editLancamentoCaixa($_codigo_caixa, 2, $_valor) . "'); ";
                $resultado = mysqli_query($this->connect(), $consulta);
                return $resultado;
            } elseif (($this->ifExistVenda($_codigo_venda)) && ($this->ifExistVenda($_codigo_forma_pagamento)) && ($_codigo_lancamento != 0) && ($value['situacao_caixa'] == 'Aberto')) {
                $consulta = " UPDATE `vendas_pagamento` SET `vendas_forma_pagamento_codigo` = '$_codigo_forma_pagamento', `valor` = '$_valor' WHERE `lancameto_codigo` = '" . $financeiro->editLancamentoCaixa($_codigo_caixa, 2, $_valor, $_codigo_lancamento) . "' AND `vendas_venda_codigo` = '$_codigo_venda'; ";
                $resultado = mysqli_query($this->connect(), $consulta);
                return $resultado;
            }
        }
    }

    function ifExistFormaPagamento($_codigo) {
        $consulta = " SELECT count(`codigo`) as cont FROM `vendas_forma_pagamento` where codigo = $_codigo ; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        foreach ($resultado as $value) {
            $teste = $value['cont'];
        }
        if ($teste != 0) {
            return true;
        } else {
            return false;
        }
    }

    function listFormaPagamento($_codigo = '0', $_nome_forma_pagamento = '') {
        $filtro = " where nome like '%$_nome_forma_pagamento%' ";
        if ($_codigo != '0') {
            $filtro = $filtro . " and codigo = $_codigo ";
        }
        $consulta = " SELECT `codigo`, `nome`, `taxa`, `descricao` FROM `vendas_forma_pagamento` $filtro ; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

}

/**
 * Description of produto
 *
 * @author tiagoc
 */
class Produto extends Database {

    function listProdutos($_codigo = 0, $_nome = '', $_nome_grupo = '') {
        if ($_codigo == 0) {
            $codigo_fim = 999999;
        } else {
            $codigo_fim = $_codigo;
        }
        $consulta = " SELECT p.`codigo`, p.`grupo_produto_codigo`, p.`nome`, p.`valor_venda_padrao`, p.`home_usuario_codigo`, p.`qtd_estoque`, p.`unidade`, gp.descricao AS grupo_descricao FROM vendas_produto AS p JOIN vendas_grupo_produto AS gp ON p.grupo_produto_codigo = gp.codigo WHERE p.codigo > $_codigo and p.codigo < $codigo_fim and p.`nome` like '%$_nome%' and gp.descricao like '%$_nome_grupo%';  ; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

    function getProduto($_codigo) {
        $consulta = " SELECT count(p.codigo) as cont, p.* , gp.descricao as grupo_descricao FROM vendas_produto as p join vendas_grupo_produto as gp on p.grupo_produto_codigo = gp.codigo where p.codigo = '$_codigo' ; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

}

/**
 * Description of Financeiro
 *
 * @author tiagoc
 */
class Financeiro extends Database {

    /**
     * Campos Resultado
     * codigo | descricao | sinal
     * @return consulta
     */
    function listTipoLancamento() {
        $consulta = " SELECT codigo, descricao, sinal FROM financeiro_tipo_lancamento; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

    /**
     * Campos Resultado
     * @param type $_data_caixa
     * @param type $_decricao_caixa
     * @param type $_codigo_caixa
     * @return type `codigo` | `data_caixa` | `descricao` | `situacao_caixa` | `usuario_codigo`
     */
    function listCaixas($_data_caixa = '', $_decricao_caixa = '', $_codigo_caixa = '') {
        if ($_data_caixa != '') {
            $filtro = " and `data_caixa` = '$_data_caixa' ";
        } else {
            $filtro = "";
        }
        if ($_codigo_caixa != '') {
            $filtro = $filtro . " and `codigo` = '$_codigo_caixa' ";
        } else {
            $filtro = $filtro . "";
        }
        $consulta = " SELECT `codigo`, `data_caixa`, `descricao`, `situacao_caixa`, `usuario_codigo` FROM `financeiro_caixa` where descricao like '%$_decricao_caixa%' $filtro ; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

    /**
     * @param type $_data_caixa
     * @param type $_decricao_caixa
     * @param type $_codigo_caixa
     * @param type $_codigo_lancamento
     * @param type $_descricao_lancamento
     * @return type codigo | caixa_codigo | data_caixa | descricao_caixa | situacao_caixa | valor | data_hora | lancamento_codigo | lancamento_descricao
     */
    function listLancamentosCaixas($_data_caixa = '', $_decricao_caixa = '', $_codigo_caixa = '', $_codigo_lancamento = '', $_descricao_lancamento = '') {
        $filtro = $this->filtroListLancamentosCaixas($_data_caixa, $_codigo_caixa, $_codigo_lancamento, $_descricao_lancamento);
        $consulta = " SELECT cl.codigo, cl.caixa_codigo, c.data_caixa, c.descricao as descricao_caixa, c.situacao_caixa, cl.valor, cl.data_hora, tl.codigo as lancamento_codigo, tl.descricao as lancamento_descricao FROM financeiro_caixa AS c JOIN financeiro_caixa_lancameto AS cl ON cl.caixa_codigo = c.codigo JOIN financeiro_tipo_lancamento AS tl ON tl.codigo = cl.financeiro_tipo_lancamento_codigo WHERE c.descricao like '%$_decricao_caixa%'  $filtro ; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

    private function filtroListLancamentosCaixas($_data_caixa = '', $_codigo_caixa = '', $_codigo_lancamento = '', $_descricao_lancamento = '') {
        $filtro = "";
        if ($_data_caixa != '') {
            $filtro = " and c.data_caixa = '$_data_caixa' ";
        }
        if ($_codigo_caixa != '') {
            $filtro = $filtro . " and cl.caixa_codigo = '$_codigo_caixa' ";
        }
        if ($_codigo_lancamento != '') {
            $filtro = $filtro . " and cl.codigo = '$_codigo_caixa' ";
        }
        if ($_descricao_lancamento != '') {
            $filtro = $filtro . " and tl.descricao = '$_codigo_caixa' ";
        }
        return $filtro;
    }

    /**
     * 
     * @param type $_codigo_caixa
     * @param type $_tipo_lancamento
     * @param type $_valor
     * @param type $_codigo_lancamento
     * @return type codigo_lancamento
     */
    function editLancamentoCaixa($_codigo_caixa, $_tipo_lancamento, $_valor, $_codigo_lancamento = 0) {
        date_default_timezone_set("America/Bahia");
        $data_atual = date('Y-m-d H:i:s');
        $_usuario_codigo = "1";
        $dados_caixa = $this->listCaixas('', '', $_codigo_caixa);
        foreach ($dados_caixa as $value) {
            if (($_codigo_lancamento == 0) && ($value['situacao_caixa'] == 'Aberto')) {
                $consulta = " INSERT INTO `financeiro_caixa_lancameto` (`caixa_codigo`,`financeiro_tipo_lancamento_codigo`,`valor`,`data_hora`,`usuario_codigo`) VALUES ('$_codigo_caixa','$_tipo_lancamento','$_valor','$data_atual','$_usuario_codigo'); ";
                $resultado = mysqli_query($this->connect(), $consulta);
                $consulta1 = " select max(codigo) as codigo_lancamento from financeiro_caixa_lancameto;";
                $resultado1 = mysqli_query($this->connect(), $consulta1);
                foreach ($resultado1 as $value) {
                    return $value['codigo_lancamento'];
                }
            } elseif (($value['situacao_caixa'] == 'Aberto')) {
                $consulta = " UPDATE `financeiro_caixa_lancameto` SET `financeiro_tipo_lancamento_codigo` = '$_tipo_lancamento', `valor` = '$_valor', `data_hora` = '$data_atual', `usuario_codigo` = '$_usuario_codigo' WHERE `codigo` = '$_codigo_lancamento'; ";
                $resultado = mysqli_query($this->connect(), $consulta);
                return $_codigo_lancamento;
            }
        }



        return;
    }

    /**
     *  Campos Resultado
     *  caixa_codigo | valor_final  | qtd_lancamento
     * @param type $_codigo_caixa
     * @return type
     */
    function getResumoCaixa($_codigo_caixa) {
        $consulta = " select t1.caixa_codigo , sum(t1.valor_extrato) as valor_final, count(codigo) as qtd_lancamento from (SELECT cl.codigo, cl.caixa_codigo, cl.financeiro_tipo_lancamento_codigo, (tl.sinal * cl.valor) AS valor_extrato, tl.sinal, cl.valor, cl.data_hora, tl.descricao FROM financeiro_caixa_lancameto cl JOIN financeiro_tipo_lancamento AS tl ON tl.codigo = cl.financeiro_tipo_lancamento_codigo) as t1 where t1.caixa_codigo = '$_codigo_caixa'  ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

}

/**
 * Description of usuario
 *
 * @author tiagoc
 */
class Usuario extends Database {

    function getSenhaEncriptada($_senha) {
        $resultado = sha1($_senha);
        return $resultado;
    }

    function getUsuario($_codigo) {
        $consulta = " SELECT * FROM home_usuario where codigo = '$_codigo'; ";
        $resultado = mysqli_query($this->connect(), $consulta);
        return $resultado;
    }

}
