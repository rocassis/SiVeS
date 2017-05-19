# Sistema Integrado de Vendas - SiVeSe
Baseado no Sistema de Controle de Estoque -> Digital Arte - Soluções e Impressos
http://www.digitalarte.com.br

Recursos do sistema
----------------------------------------------------------------------------------------------------------------------------------------
1 - Cadastro de produtos( nome, unidade e estoque) -- ok alterado 
2 - Dedução de quantidade de estoque em tempo de consumo -- novo
3 - Cadastro, edição e exclusão de clientes --- ok original
4 - Cadastro, edição e exclusão de vendedores -- ok alterado
5 - Vendas (cadastro, edição e exclusão de vendas e produtos) -- ok alterado
6 - Adição e subtração da quantidade de produto na venda -- novo
7 - Cadastro, edição e exclusão de serviços -- ok original
8 - Ordem de serviço com venda de produtos -- ok original
9 - Configuração do sistema -- ok original


Intruções de instalação e configuração
----------------------------------------------------------------------------------------------------------------------------------------

O arquivo para criação do banco de dados está dentro da pasta 'docs' com o nome de script.sql.
Antes de executar o script, é necessário criar um novo Schema, pois o script só contempla as criações das tabelas e insert de usuário e permissão de administrador.

----------------------------------------------------------------------------------------------------------------------------------------

Dentro da pasta 'Application' -> 'Config' edite o arquivo 'config.php' na seguinte linha. 

$config['base_url']	= '';

Aqui você colocará a url base de sua aplicação, se colocar na raiz do servidor por exemplo colocará assim: $config['base_url']	= 'http://127.0.0.1'; ou 'http://dominio.com'

Se colocar dentro de uma pasta com nome por exemplo 'sistema' ficará assim:
$config['base_url']	= 'http://127.0.0.1/sistema';


Obs: Em alguns casos no ambiente local (localhost) é necessário especificar a porta.
Exemplo: $config['base_url']	= 'http://127.0.0.1:3000/sistema';

---------------------------------------------------------------------------------------------------------------------------

Dentro da pasta 'Application' -> 'Config' edite o arquivo 'database.php' e coloque os dados de acesso ao banco de dados. 

---------------------------------------------------------------------------------------------------------------------------

O logotipo se encontra dentro da pasta assets/img. Caso queira trocá-lo, basta substituir pelo logo desejado com o mesmo nome (logo.png). 

----------------------------------------------------------------------------------------------------------------------------------------

Dados de acesso inicial
Email: admin@admin.com
Senha: 123456



Modificado por:
Robert de Assis
rc.assis.job@bol.com.br
