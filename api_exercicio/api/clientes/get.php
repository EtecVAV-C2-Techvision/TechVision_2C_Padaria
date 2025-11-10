<?php
            if ($acao == '' && $param == '') { echo json_encode(['ERRO' => 'Caminho não encontrado!']);}

            if ($acao == 'lista' && $param == ''){
                $db = DB::connect();
                $rs = $db->prepare("select * from clientes order by nome");
                $rs->execute();
                $obj = $rs->fetchAll(PDO::FETCH_ASSOC);

                if($obj){
                    echo json_encode(["dados" => $obj]);
                }   else{
                    echo json_encode(["dados" => 'Não existem dados para retornar.']);
                }
            }
            
            if ($acao == 'lista' && $param !== ''){
                $db = DB::connect();
                $rs = $db->prepare("select * from clientes where id ={$param}");
                $rs->execute();
                $obj = $rs->fetchObject();

                if($obj){
                    echo json_encode(["dados" => $obj]);
                }   else{
                    echo json_encode(["dados" => 'Não existem dados para retornar.']);
                }
            }