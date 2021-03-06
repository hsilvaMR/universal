<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Meta Tags
    |--------------------------------------------------------------------------
    */
    'siteTitulo' => 'Universal',
    'siteDescricao' => 'Backoffice desenvolvido pela Mcode (84246 36 22766 337626337 636337).',

    'loginTitulo' => 'Login | Universal',
    'restoreTitulo' => 'Recuperar | Universal',
    'dashboardTitulo' => 'Painel | Universal',
    'settingsTitulo' => 'Configurações | Universal',
    'accountTitulo' => 'Conta | Universal',
    'managersTitulo' => 'Gestores | Universal',
    'managerTitulo' => 'Gestor | Universal',
    'notificationsTitulo' => 'Notificações | Universal',
    'notificationTitulo' => 'Notificação | Universal',
    'slidesTitulo' => 'Slides | Universal',
    'slideTitulo' => 'Slide | Universal',
    'awardsTitulo' => 'Prémios | Universal',
    'awardTitulo' => 'Prémio | Universal',
    'giveawaysTitulo' => 'Passatempos | Universal',
    'giveawayTitulo' => 'Passatempo | Universal',
    'questionsTitulo' => 'Perguntas | Universal',
    'questionTitulo' => 'Pergunta | Universal',
    'faqsTitulo' => 'FAQs | Universal',
    'faqTitulo' => 'FAQ | Universal',
    'contactsTitulo' => 'Contactos | Universal',
    'usersTitulo' => 'Utilizadores | Universal',
    'userTitulo' => 'Utilizador | Universal',
    'companiesTitulo' => 'Empresas | Universal',
    'ordersTitulo' => 'Encomendas | Universal',

    'adressesTitulo' => 'Endereços | Universal',
    'sellersTitulo' => 'Comerciantes | Universal',
    'sellerTitulo' => 'Comerciante | Universal',
    'productsTitulo' => 'Produtos | Universal',
    'technicalInfoTitulo' => 'Informações Técnicas | Universal',
    'labelsTitulo' => 'Rótulos | Universal',
    'labelTitulo' => 'Rótulo | Universal',
    'docManagementTitulo' => 'Gestão Documental | Universal',

    'certificationsTitulo' => 'Certificações | Universal',
    'certificationTitulo' => 'Certificação | Universal',
    'processesTitulo' => 'Processos | Universal',
    'processTitulo' => 'Processo | Universal',
    'activitiesTitulo' => 'Actividades | Universal',
    'activityTitulo' => 'Actividade | Universal',
    'tasksTitulo' => 'Tarefas | Universal',
    'taskTitulo' => 'Tarefa | Universal',
    'acronymsTitulo' => 'Siglas | Universal',
    'acronymTitulo' => 'Sigla | Universal',

    /*
    |--------------------------------------------------------------------------
    | Email
    |--------------------------------------------------------------------------
    */
    /* assuntos */
    'subjectWelcome' => 'Bem-vindo ao backoffice',
    'subjectActivate' => 'Por favor active a sua conta',
    'subjectRestore' => 'Por favor recupere a sua password',
    'subjectApprovedCompany' => 'Empresa aprovada',
    'subjectDisapprovedCompany' => 'Empresa reprovada',
    'subjectApprovedAccount' => 'Conta aprovada',
    'subjectDisapprovedAccount' => 'Conta reprovada',
    /* novo-utilizador */
    'welcomeTi' => 'Olá',
    'welcomeTx' => 'Foi adicionado como gestor de backoffice da <a href="' . asset('') . '" style="text-decoration:none;color:#2fb385;cursor:default;">Universal</a>, defina uma password para poder aceder em <a href="' . route('loginPageB') . '" style="text-decoration:none;color:#2fb385;cursor:default;">' . route('loginPageB') . '</a>.',
    'welcomeBt' => 'Definir password',
    /* validar-conta */
    'activateTx' => 'Obrigado por se registar.<br>Por favor active a sua conta.',
    'activateBt' => 'Activar conta',
    /* recuperar-password */
    'restoreTx' => 'Por favor recupere a sua password.',
    'restoreBt' => 'Recuperar password',
    /* empresa-aprovada */
    'ApprovedCompanyTx' => 'A empresa foi aprovada,<br>agora já pode editar em todos os separadores.',
    'ApprovedCompanyBt' => 'Iniciar sessão',
    /* empresa-reprovada */
    'DisapprovedCompanyTx' => 'A empresa foi reprovada,<br>edite os dados e volte a submeter para aprovação.',
    'DisapprovedCompanyBt' => 'Iniciar sessão',
    /* conta-aprovada */
    'ApprovedAccountTx' => 'A sua conta foi aprovada,<br>agora já pode realizar encomendas.',
    'ApprovedAccountBt' => 'Iniciar sessão',
    /* conta-reprovada */
    'DisapprovedAccountTx' => 'A sua conta foi reprovada,<br>edite os seus dados e submeta para aprovação.',
    'DisapprovedAccountBt' => 'Iniciar sessão',


    /* footer */
    'doesntWorkTx' => 'Se o botão não funcionar, copie e cole o seguinte link no seu browser',
    /*
    |--------------------------------------------------------------------------
    | DatePicker
    |--------------------------------------------------------------------------
    */
    'days' => '["Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"]',
    'daysShort' => '["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb", "Dom"]',
    'daysMin' => '["D", "S", "T", "Q", "Q", "S", "S", "D"]',
    'months' => '["JAN", "FEV", "MAR", "ABR", "MAI", "JUN", "JUL", "AGO", "SET", "OUT", "NOV", "DEZ"]',
    'monthsShort' => '["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"]',

    /*
    |--------------------------------------------------------------------------
    | Upload Imagem
    |--------------------------------------------------------------------------
    */
    'Unsupportedextension' => 'Extensão não suportada:',

    /*
    |--------------------------------------------------------------------------
    | Backoffice
    |--------------------------------------------------------------------------
    */
    /* Login */
    'Welcome' => 'Bem-vindo',
    'Email' => 'Email',
    'email' => 'email',
    'Password' => 'Password',
    'password' => 'password',
    'forgotPassword' => 'Esqueceu-se da password?',
    'logIn' => 'Entrar',
    'NonExistentEmail' => 'O endereço de email que inseriste não corresponde a uma conta.',
    'SuccessfullySubmitted' => 'Submetido com sucesso. Verifique o seu email!',
    'LoginSuccessfully' => 'Login realizado com sucesso.',
    'incorrectPassword' => 'A palavra-passe que inseriu está incorreta.',
    'accountLocked' => 'A sua conta encontra-se bloqueada.',
    'accountStillPeding' => 'A sua conta ainda se encontra pendente. Clique no botão para recuperar password e ativar a sua conta.',

    'restoreAccount' => 'Recupere a sua conta.',
    'youKnow' => 'Sabe a password, inicie sessão.',
    'Restore' => 'Recuperar',
    'resetPassword' => 'Redefina a password.',
    'newPassword' => 'Nova password',
    'Save' => 'Guardar',
    'savedSuccessfully' => 'Guardado com sucesso',
    'invalidRequest' => 'Pedido inválido. Solicite um novo pedido de recuperação de password.',
    'beginSession' => 'Iniciar Sessão',
    'finalizeOrder' => 'Finalizar Encomenda',

    /* menu */
    'Account' => 'Conta',
    'Managers' => 'Gestores',
    'Logout' => 'Sair',
    'Dashboard' => 'Painel',
    'Website' => 'Website',
    'UserArea' => 'Área Utilizador',
    'CommercialArea' => 'Área Comercial',
    'Definitions' => 'Definições',
    'Slide' => 'Slide',
    'Awards' => 'Prémios',

    'Giveaways' => 'Passatempos',
    'littleCheese' => 'Queijinho',
    'Questions' => 'Perguntas',
    'FAQs' => 'FAQs',
    'Contacts' => 'Contactos',
    'Contact' => 'Contacto',
    'Users' => 'Utilizadores',
    'Companies' => 'Empresas',
    'Orders' => 'Encomendas',
    'Adresses' => 'Endereços',
    'Settings' => 'Configurações',
    'DocumentManagement' => 'Gestão Documental',
    'Certifications' => 'Certificações',
    'Documents' => 'Documentos',

    /* configuracoes */
    'AddSettings' => 'Adicionar Configuração',
    'AllSettings' => 'Todas as Configurações',
    'NewSetting' => 'Nova Configuração',
    'EditSetting' => 'Editar Configuração',
    'set_days' => 'dias',

    'SET_ponto_em_euros' => 'O custo de cada ponto em falta que o utilizador terá de pagar no carrinho.',
    'SET_premio_notificacao_dias' => 'Número de dias a partir do qual o utilizador pode pedir informações sobre o produto encomendado.',
    'SET_euros_em_pontos_empresa' => 'Valor gasto nas encomenda pelo comerciante pelo qual lhe é atribuido pontos.',
    'SET_margem_lucro' => 'Margem de lucro em percentagem aplicada por defeito aos produtos.',
    'SET_ticket_contacto' => 'Mensagem visível nos tickets, na área do comerciante.',


    /* rótulos */
    'Labels' => 'Rótulos',
    'unit' => 'unidade',
    'units' => 'unidades',
    'point' => 'ponto',
    'points' => 'pontos',
    'box' => 'caixa',
    'Box' => 'Caixa',
    'Serie' => 'Serie',
    'Nodata' => 'Sem dados!',
    'Trader' => 'Comerciante',

    'AddLabel' => 'Adicionar Rótulo',
    'NewLabel' => 'Novo Rótulo',
    'EditLabel' => 'Editar Rótulo',



    'InitialLabel' => 'Rótulo inicial',
    'FinalLabel' => 'Rótulo final',
    'PortionOfCheese' => 'Porção de Queijo',
    'AddProduction' => 'Adicionar Produção',
    'AllLabels' => 'Todos os Rótulos',
    'NewProduction' => 'Nova Produção',
    'EditProduction' => 'Editar Produção',
    'InsertProduct' => 'Introduza o produto',
    'InsertLabels' => 'Introduza o rótulo de início e o rótulo de fim',
    'InitialLabel_txt' => 'Este rótulo inicial está a ser utilizado noutro produto.',
    'FinalLabel_txt' => 'Este rótulo final está a ser utilizado noutro produto.',
    'SelectedProduct' => 'Selecione o produto',
    'NotExistLabel_txt' => 'Este rótulo não existe.',
    'GenerateCodes' => 'Gerar Códigos',
    'HowManyCodeGenerate_txt' => 'Quantos códigos quer gerar?',


    /* conta */
    'Name' => 'Nome',
    'Language' => 'Idioma',
    'SavingR' => 'A guardar...',
    'oldPassword' => 'Antiga password',
    'least6characteres' => 'A nova password tem de ter no mínimo 6 caracteres.',

    'Delete' => 'Apagar',
    'DeleteAvatar' => '<p>Tem a certeza que deseja apagar o <b>Avatar</b>?</p>',
    'DeleteLogotipo' => '<p>Tem a certeza que deseja apagar o <b>Logotipo</b>?</p>',
    'DeleteLine' => '<p>Tem a certeza que deseja apagar esta linha?</p>',
    'Cancel' => 'Cancelar',

    'Saved' => 'Guardado',
    'Back' => 'Voltar',
    'Ok' => 'Ok',

    /* gestores */
    'Administrator' => 'Administrador',
    'Support' => 'Suporte',
    'Commercial' => 'Comercial',
    'Agent' => 'Agente',
    'Head' => 'Chefe',
    'Manager' => 'Gestor',
    'Editor' => 'Editor',
    'User' => 'Utilizador',
    'user' => 'utilizador',
    'Viewer' => 'Visualizador',
    'Guest' => 'Convidado',
    'Active' => 'Ativo',
    'Pending' => 'Pendente',
    'Blocked' => 'Bloqueado',
    'Image' => 'Imagem',
    'lastAcess' => 'Último acesso',
    'Type' => 'Tipo',
    'Status' => 'Estado',
    'Option' => 'Opção',
    'Edit' => 'Editar',
    'noRecords' => 'Sem registos',
    'DeleteUser' => '<p>Tem a certeza que deseja apagar este <b>utilizador</b>?</p>',
    'addNew' => 'Adicionar Novo',
    'newManager' => 'Novo gestor',
    'editManager' => 'Editar gestor',
    'Portuguese' => 'Português',
    'English' => 'Inglês',
    'Spanish' => 'Espanhol',
    'Additional' => 'Adicional',
    'invalidEmail' => 'Email inválido!',
    'emailAlready' => 'Este email já tem conta associada!',
    'All' => 'Todos',
    'Permissions' => 'Permissões',

    /*companies*/
    'NIF' => 'NIF',
    'CAE' => 'CAE',
    'Phone_call' => 'Contacto telefónico',
    'NumberPointsSale' => 'Número de pontos de vendas',
    'LastYearSalesVolume' => 'Volume de vendas do último ano',
    'LastYearEBITDA' => 'EBITDA do último ano',
    'Permanent_Company_Certificate' => 'Certidão permanente da empresa',
    'Invoice_Type' => 'Tipo de fatura',
    'Additional_information' => 'Informação adicional',
    'Unified_Invoice_txt' => 'Fatura unificada - vários endereços de compra numa fatura/recibo',
    'Separate_Invoice_txt' => 'Fatura separada - uma fatura/recibo por endereço de compra',
    'IES_txt' => 'IES (Informação empresarial simplificada)',
    'days_txt' => 'dias',
    'Payment_Term' => 'Prazo de Pagamento',
    'WarehouseAddress' => 'Morada de Armazém',
    'OrderDocument' => 'Documento da Encomenda',
    'ProcessingStartDate' => 'Data de Início do Processamento',
    'EditAdresses' => 'Editar Endereços',
    'EditSellers' => 'Editar Comerciantes',
    'AddCompany' => 'Adicionar Empresa',
    'Insert_IES_year_txt' => 'Introduza o IES do respectivo ano.',
    'Invalid_field_NIF' => 'Campo NIF inválido.',
    'Invalid_Ebitda_field_txt' => 'Campo Ebitda do último ano inválido.',
    'Invalid_Volume_field_txt' => 'Campo Volume de vendas do último ano inválido.',
    'FormatInvalidEmail' => 'Formato de email inválido, introduza um email válido.',
    'Email_repite' => 'O email que inseriu já existe.',
    'Field_company_certificate_txt' => 'Campo certidão permanente da empresa vazio.',
    'Field_CAE_txt' => 'Campo CAE inválido.',
    'Field_Name_txt' => 'Campo nome por preencher.',

    'allCompanies' => 'Todas as empresas',
    'editCompany' => 'Editar empresa',

    /*Companies - Estados*/
    'Suspended' => 'Suspenso',
    'Disapproved' => 'Reprovado',

    /*Orders*/
    'Order' => 'Encomenda',
    'Warehouse_Orders' => 'Encomendas por armazém',
    'allOrders' => 'Todas as encomendas',
    'editOrder' => 'Editar Encomenda',
    'Company' => 'Empresa',
    'company' => 'empresa',
    'Seller' => 'Comerciante',
    'Sellers' => 'Comerciantes',
    'Reference' => 'Referência',
    'Document' => 'Documento',
    'SubTotal' => 'Subtotal',
    'Total' => 'Total',
    'StartProcessing' => 'Início do Processamento',
    'Proforma' => 'Proforma',
    'Guide' => 'Guia',
    'Invoice' => 'Fatura',
    'Proof' => 'Comprovativo',
    'Receipt' => 'Recibo',
    'Expedition' => 'Expedição',
    'ProformaDate' => 'Data de Proforma',
    'GuideDate' => 'Data de Guia',
    'ShippingDate' => 'Data de Expedição',
    'InvoiceDate' => 'Data de Fatura',
    'PaymentReceipt' => 'Comprovativo de Pagamento',
    'ProofDate' => 'Data de Comprovativo',
    'DateReceived' => 'Data de Recibo',
    'AddProduct' => 'Adicionar Produto',
    'AddOrder' => 'Adicionar Encomenda',
    'NewOrder' => 'Nova Encomenda',
    'SelectedCompany' => 'Selecione a empresa',
    'CleanData' => 'Limpar Dados',
    'United' => 'Unidade',
    'Price' => 'Preço',
    'Amount' => 'Quantidade',
    'Value' => 'Valor',
    'DeleteCompany' => '<p>Tem a certeza que deseja apagar esta <b>empresa</b>?</p>',
    'DeleteOrder' => 'Eliminar Encomenda',
    'DeleteOrder_txt' => '<p>Tem a certeza que deseja apagar esta <b>encomenda</b>?</p>',
    'Obs' => 'Observações',
    'In_processing' => 'Em processamento',
    'fatura_vencida' => 'Fatura vencida',
    'Sent' => 'Enviado',
    'Completed' => 'Concluída',
    'completed' => 'concluída',
    'registered' => 'registada',
    'Registered' => 'Registada',
    'partially_dispatched' => 'Expedida parcialmente',
    'Dispatched' => 'Expedida',
    'EmptyAdresses_txt' => 'Não existem moradas de armazém. Adicione novas moradas.',
    'EditDocuments' => 'Editar documentos',
    'EditDocument' => 'Editar documento',
    'Box' => 'Caixa',
    'articles' => 'artigos',
    'article' => 'artigo',
    'Article' => 'Artigo',
    'Unity' => 'Unidade',
    'Total_partial' => 'Total parcial',
    'Carrying' => 'A transportar',
    'Price' => 'Preço',
    'Amount_Total' => 'Valor Total',
    'Details' => 'Detalhes',


    /*Addresses*/
    'CustomName' => 'Nome Personalizado',
    'Adress' => 'Morada',
    'Telefone' => 'Telefone',
    'Responsible' => 'Responsável',
    'EmailResponsible' => 'Email do responsável',
    'TelefoneResponsible' => 'Telefone do responsável',
    'Type' => 'Tipo',
    'AllAdresses' => 'Todas as moradas',
    'EditAdresses' => 'Editar Morada',
    'AddAdress' => 'Adicionar Morada',
    'TypeAdress' => 'Tipo de Endereço',
    'OfficeAdress' => 'Endereço da Sede',
    'AccountingAddress' => 'Endereço de Contabilidade',
    'PurchaseAddress' => 'Endereço de Compras',
    'Name_to_show' => 'Nome a mostrar',
    'Postal_Code' => 'Código Postal',
    'City' => 'Cidade',
    'Country' => 'País',
    'Fax' => 'Fax',
    'Office' => 'Cargo',
    'Approved' => 'Aprovado',
    'OnApproval' => 'Em aprovação',
    'Field_custom_name_txt' => 'Campo nome personalizado por preencher.',
    'Field_name_contact_person_txt' => 'Campo nome do responsável por preencher.',
    'Field_office_contact_person_txt' => 'Campo cargo do responsável por preencher.',
    'Field_email_contact_person_txt' => 'Campo email do responsável por preencher.',
    'Format_Invalid_Contact_Person' => 'Contacto do responsável inválido, introduza um contacto válido.',
    'Format_Invalid_Adress' => 'Contacto telefónico do endereço inválido, introduza um contacto válido.',
    'Field_street_txt' => 'Campo rua por preencher.',
    'Field_code_txt' => 'Campo código-postal por preencher.',
    'Field_code_invalid_txt' => 'Campo código-postal inválido.',
    'Field_city_txt' => 'Campo cidade por preencher.',
    'Field_country_txt' => 'Campo país por preencher.',
    'DeleteAdress' => '<p>Tem a certeza que deseja apagar esta <b>morada</b>?</p>',

    /*Comerciantes*/
    'Newsletter' => 'Newsletter',
    'AllSeller' => 'Todos os comerciantes',
    'FileValidate' => 'Ficheiro de validação',
    'LegalRepresentative' => 'Representante Legal',
    'PersonContact' => 'Pessoa de Contacto',
    'AddSeller' => 'Adicionar Comerciante',
    'Field_name_txt' => 'Campo nome próprio por preencher.',
    'Field_email_txt' => 'Campo email por preencher.',
    'Field_contact_txt' => 'Campo contacto telefónico por preencher.',
    'Field_file_txt' => 'Campo ficheiro de validação por preencher.',
    'Field_office_txt' => 'Campo cargo por preencher.',
    'Purchase_Addresses' => 'Endereços de Compras',
    'DeleteSeller' => '<p>Tem a certeza que deseja apagar este <b>comerciante</b>?</p>',
    'EditSeller' => 'Editar Comerciante',
    'Own_name' => 'Nome próprio',
    'Nickname' => 'Apelido',

    /*comunicação*/
    'communication' => 'Communicação',
    'comuniBradCromb' => 'Todas as Comunicação',
    'communiFile' => 'Imagem',
    'communNome' => 'Nome',
    'comunDesc' => 'Descrição',
    'communUpdate' => 'atualização',
    'communTipo' => 'Tipo',
    'commuLink' => 'Link',
    'commuAction' => 'Ação',
    'commuDownload' => 'Download',
    'commuShare' => 'Share link',
    'commuBtnAdd' => 'Adicionar',
    'comunicTitulo' => 'Comunicação | Universal',
    'comuniFrName' => 'Nome',
    'comuniFrDesc' => 'Descrição',
    'comuniFrType' => 'Tipo',
    'comuniFrFile' => 'Ficheiro',
    'comuniFrTypeR' => 'Rótulo',
    'comuniFrTypeImg' => 'Imagen',



    /*Products*/
    'Products' => 'Produtos',
    'Product' => 'Produto',
    'product' => 'produto',
    'IVA' => 'IVA',
    'AllProducts' => 'Todos os produtos',
    'AddProduct' => 'Adicionar Produto',
    'NewProduct' => 'Novo Produto',
    'EditProduct' => 'Editar Produto',
    'TraderPoints' => 'Pontos Comerciante',
    'DeleteProduct' => 'Eliminar Produto',
    'DeleteProduct_txt' => '<p>Tem a certeza que deseja apagar este <b>produto</b>?</p>',
    'UnitedPrice' => 'Preço unitário',

    /*Informações Técnicas*/
    'TechnicalInformation' => 'Informações Técnicas',
    'new' => 'novo',
    'updated' => 'actualizado',
    'File' => 'Ficheiro',
    'EditInformation' => 'Editar Informações',
    'AllInformation' => 'Todas Informações',
    'AddInformation' => 'Adicionar Informação',
    'DeleteInformation' => 'Eliminar Informação Técnica',
    'DeleteInformation_txt' => '<p>Tem a certeza que deseja apagar esta <b>informação técnica</b>?</p>',
    'document' => 'documento',

    /* notifications */
    'Notifications' => 'Notificações',
    'newNotification' => 'Nova notificação',
    'editNotification' => 'Editar notificação',
    'Notification' => 'Notificação',
    'Date' => 'Data',
    'Go' => 'Ir',
    'Comission' => 'Comissão',
    'Payment' => 'Pagamento',
    'Information' => 'Informacao',
    'Link' => 'Link',
    'View' => 'Vista',
    'erroMsgEmpty' => 'Por favor, escreva a notificação.',
    'addNotification' => 'Adicionar notificação',
    'sendnotification' => 'enviar notificação',
    /* predefinidas */
    'defaultNotification1' => 'O contacto ###id## foi actualizado.',

    /* painel */
    'goToPage' => 'Ir para a página',
    'latestUsers' => 'Últimos utilizadores',
    'latestCommissions' => 'Últimas comissões',
    'latestAgents' => 'Últimos agentes',
    'latestCustomers' => 'Últimos clientes',
    'latestPayments' => 'Últimos pagamentos',
    'latestOrders' => 'Últimas encomendas',
    'OrdersInProcessing' => 'Encomendas em processamento',
    'latestCompanies' => 'Últimas empresas',
    'latestSellers' => 'Últimos comerciantes',
    'latestContacts' => 'Últimos contactos',
    'latestInformation' => 'Últimas informações técnicas',
    'latestPastime' => 'Últimos passatempos',

    /* website */
    'allSlides' => 'Todos os slides',
    'newSlide' => 'Novo slide',
    'editSlide' => 'Editar slide',
    'Title' => 'Titulo',
    'Text' => 'Texto',
    'buttonText' => 'Texto botão',
    'Online' => 'Online',
    'smallImage' => 'Imagem pequena',
    'backgroundColor' => 'Cor de fundo',
    'Command' => 'Ordem',

    'tipoSlideImgTexto' => 'Img | Txt',
    'tipoSlideImg' => 'Img',

    'Blue' => 'Azul',
    'Pink' => 'Rosa',
    'Yellow' => 'Amarelo',
    'Lilac' => 'Lilás',

    /* prémios */
    'editRequested' => 'Editar Solicitação',
    'allAwards' => 'Todos os prémios',
    'awardsRequested' => 'Prémios solicitados',
    'newAward' => 'Novo prémio',
    'editAward' => 'Editar prémio',
    'Validity' => 'Validade',
    'Description' => 'Descrição',
    'Points' => 'Pontos',
    'PointsUser' => 'Pontos Utilizador',
    'PointsCompany' => 'Pontos Empresa',
    'Stock' => 'Stock',
    'expirationDate' => 'Data de validade',
    'both' => 'ambos',
    'Both' => 'Ambos',
    'PremiumAvailable' => 'Prémio disponível',
    'ViewAwards' => 'Ver Prémios',
    'DateOfRequest' => 'Data de Pedido',
    'Variant' => 'Variante',
    'Variants' => 'Variantes',
    'SendDate' => 'Data de Envio',
    'in_processing' => 'em processamento',
    'sent' => 'enviado',
    'WithoutVariant' => 'Sem variante',
    'AwardAcquisition' => 'Aquisição do Prémio',
    'TheCompanyNotPremium' => 'A empresa em questão não tem pontos suficientes para adquirir ao prémio.',
    'EnterVariantsLanguages_txt' => 'Introduza as variantes em todas as linguagens.',
    'EditRequest' => 'Editar Pedido',
    'Request' => 'Pedido',
    'PointsUsed' => 'Pontos Utilizados',
    'DateRequest' => 'Data do Pedido',
    'StatusRequest' => 'Estado do Pedido',
    'Code' => 'Código',
    'Serial' => 'Série',
    'dateConclusion' => 'Data de Conclusão',
    'PointsNeeded' => 'Pontos Necessários',
    'DataBilling' => 'Dados de Faturação',
    'DeliveryData' => 'Dados de Entrega',

    /* variantes */
    'allVariants' => 'Todas as variantes',
    'editVariant' => 'Editar variante',
    'newVariant' => 'Nova variante',

    /* passatempos */
    'allGiveaways' => 'Todos os passatempos',
    'newGiveaway' => 'Novo passatempo',
    'editGiveaway' => 'Editar passatempo',

    'Active' => 'Ativo',
    'active' => 'ativo',
    'Inactive' => 'Desativo',
    'QfC' => 'PpQ',

    'Regulation' => 'Regulamento',
    'startDate' => 'Data de início',
    'endDate' => 'Data de fim',
    'Award' => 'Prémio',
    'featuredImage' => 'Imagem de destaque',
    'squareImage' => 'Imagem quadrada',
    'Winners' => 'Vencedores',
    'winnersType' => 'Tipo de vencedores',

    'tipoWinner1' => 'Nome do vencedor',
    'tipoWinner2' => 'Nomes dos vencedores',
    'tipoWinner3' => 'Nenhum vencedor',
    'tipoWinner4' => 'Muitos vencedores',

    /* perguntas */
    'allQuestions' => 'Todas as perguntas',
    'newQuestion' => 'Nova pergunta',
    'editQuestion' => 'Editar pergunta',
    'Answer' => 'Resposta',
    'publicationDate' => 'Data de publicação',
    'datePublicationQuestion' => 'Data de publicação da pergunta',
    'datePublicationAnswer' => 'Data de publicação da resposta',
    'Facebook' => 'Facebook',
    'Instagram' => 'Instagram',
    'Tag' => 'Tag',
    '1award' => '1º Prémio',
    '2award' => '2º Prémio',
    '3award' => '3º Prémio',
    '4award' => '4º Prémio',
    '5award' => '5º Prémio',
    'allFAQs' => 'Todas as faqs',
    'newFAQ' => 'Nova faq',
    'editFAQ' => 'Editar faq',
    'Question' => 'Pergunta',

    /* contactos */
    'allContacts' => 'Todos os contactos',
    'Message' => 'Mensagem',

    /* utilizadores */
    'allUsers' => 'Todos os utilizadores',
    'newUser' => 'Novo utilizador',
    'editUser' => 'Editar utilizador',
    'dateOfRegistration' => 'Data de registo',
    'AccountInformation' => 'Informação Conta',
    'AI' => 'IC',
    'PersonalInformation' => 'Informação Pessoal',
    'PI' => 'IP',
    'Addresses' => 'Endereços',
    'Ad' => 'En',
    'Awards' => 'Prémios',
    'Aw' => 'Pr',
    'Po' => 'Po',
    'Token' => 'Token',
    'Lastacess' => 'Último acesso',
    'Lastlogin' => 'Último login',
    'Lang' => 'Língua',
    'Surname' => 'Apelido',
    'Changeemail' => 'Email para alteração',
    'VAT' => 'Contribuinte',
    'Phone' => 'Telefone',
    'Mobile' => 'Telemóvel',
    'Address(line1)' => 'Morada (linha 1)',
    'Address(line2)' => 'Morada (linha 2)',
    'Zipcode' => 'Código postal',
    'Quantity' => 'Quantidade',

    'BillingAddress' => 'Endereço de Faturação',
    'DeliveryAddress' => 'Endereço de Entrega',

    'Current' => 'Atual',
    'Processing' => 'Processamento',
    'Sent' => 'Enviado',
    'Concluded' => 'Concluído',
    'concluded' => 'concluído',


    /* rótulos */
    'Available' => 'Disponível',
    'Unavailable' => 'Indisponível',
    'Add' => 'Adicionar',

    'Label' => 'Rótulo',
    'GenerateLabels' => 'Gerar Rótulos',
    'IdentifyLabels' => 'Identificar Rótulos',
    'Firstlabel' => 'Primeiro rótulo',
    'Lastlabel' => 'Último rótulo',
    'firstLastLabelError' => 'Introduza o primeiro e último rótulo.',
    'selectProductError' => 'Selecione o produto.',
    'firstLabelError' => 'O primeiro rotulo não foi encontrado.',
    'lastLabelError' => 'O último rotulo não foi encontrado.',
    'productLabelError' => 'O produto não foi encontrado.',

    'ExportLabels' => 'Exportar Rótulos',
    'Firstid' => 'Primeiro id',
    'Lastid' => 'Último id',
    'Export' => 'Exportar',

    'labelsTit' => 'Rotulos-Universal',
    'labelsTitPag' => 'Rotulos',
    'fieldId' => 'Id',
    'fieldLabel' => 'Rotulo',
    'fieldInvertedid' => 'Id invertido',
    'fieldInvertedlabel' => 'Rotulo invertido',
    'fieldSerie' => 'Serie',

    /* empresas */
    'Logotipo' => 'Logotipo',
    'Vat' => 'Nif',
    'Cae' => 'Cae',
    'Ebitda' => 'Ebitda',
    'Sellingpoints' => 'Pontos de venda',
    'Salesamount' => 'Volume de vendas',
    'Percentage' => 'Percentagem',
    'Automatic' => 'Automático',
    'Manual' => 'Manual',
    'Paymentterm' => 'Prazo de pagamento',
    '30days' => '30 dias',
    '60days' => '60 dias',

    'Invoicetype' => 'Tipo de fatura',
    'UnifiedInvoiceTx' => 'Fatura unificada - vários endereços de compra numa fatura/recibo',
    'SeparateInvoiceTx' => 'Fatura separada - uma fatura/recibo por endereço de compra',

    'Permanentcertificate' => 'Certidão permanente',
    'IesTx' => 'IES - Informação empresarial simplificada (Ano - Documento)',

    'allSellers' => 'Todos os comerciantes',
    'newSeller' => 'Novo comerciante',
    'editSeller' => 'Editar comerciante',

    'OnApproval' => 'Em Aprovação',
    'C' => 'E',
    'P' => 'P',
    'A' => 'E',
    'Address' => 'Endereço',
    'Headoffice' => 'Sede',
    'Accounting' => 'Contabilidade',
    'Shopping' => 'Compras',
    'People' => 'Pessoas',
    'Representative' => 'Representante',
    'Comments' => 'Observações',
    'FinalValue' => 'Valor Final',
    'NoteCompTx' => 'Nota (No caso de reprovar a empresa)',
    'NoteSellTx' => 'Nota (No caso de reprovar o comerciante)',
    'AmountPaid' => 'Valor Pago',
    'selectedFiles' => 'ficheiros selecionados',

    'resendActivationEmail' => 'Reenviar Email de Ativação',
    'Resend' => 'Reenviar',
    'ResendEmail' => 'Reenviar Email',


    /* informações tecnicas */
    'New' => 'Novo',
    'Updated' => 'Actualizado',


    /*
    |--------------------------------------------------------------------------
    | Gestão Documental
    |--------------------------------------------------------------------------
    */
    /* certificações */
    'allCertifications' => 'Todas as certificações',
    'newCertification' => 'Nova certificação',
    'editCertification' => 'Editar certificação',
    'Processes' => 'Processos',

    'allProcesses' => 'Todos os processos',
    'newProcess' => 'Novo processo',
    'editProcess' => 'Editar processo',
    'Diagram' => 'Diagrama',
    'Activities' => 'Actividades',

    'allActivities' => 'Todas as actividades',
    'newActivity' => 'Nova actividade',
    'editActivity' => 'Editar actividade',
    'Tasks' => 'Tarefas',

    'allTasks' => 'Todas as tarefas',
    'newTask' => 'Nova tarefa',
    'editTask' => 'Editar tarefa',
    'Task' => 'Tarefa',
    'Responsible(name)' => 'Responsável (nome)',
    'Responsible(initials)' => 'Responsável (sigla)',
    'Initials' => 'Sigla',
    'ResponsibleLgd' => 'Nomes e siglas são adicionados em separado.',
    'Send' => 'Enviar',
    'Input' => 'Entrada',
    'Output' => 'Saída',
    'Acronyms' => 'Siglas',
    'ProcessMatrix' => 'Matriz do Processo',

    'allAcronyms' => 'Todas as siglas',
    'newAcronym' => 'Nova sigla',
    'editAcronym' => 'Editar sigla',



    /* documentos */
    'WhoDid' => 'Quem fez',
    'DateOfInsertion' => 'Data de Insercão',
    'WhoReview' => 'Quem revio',
    'ReviewDate' => 'Data de Revisão',
    'Process' => 'Processo',
    'CreatProcess' => 'Criar Processo',
    'CreatDocument' => 'Criar Documento',
    'CreatDiagram' => 'Criar Diagrama',
    'CreatDiagramTx' => 'Pode criar um diagrama <a class="cursor-pointer" onclick="showDiagram();">clicando aqui</a>',
    'FieldRef_txt' => 'Campo referência por preencher.',
    'FieldDoc_txt' => 'Campo ficheiro por preencher.',
    'AddFileDiagram_txt' => 'Só pode adicionar um ficheiro ou um diagrama.',
    'Version' => 'Versão',
    'LettersNumbersAccepted_txt' => 'Só são aceites letras e números.',
    'AllDocuments' => 'Todos os documentos',
    'Update' => 'Atualizar',
    'Versions' => 'Versões',
    'AuxiliaryFiles' => 'Ficheiros Auxiliares',
    'WhoRevisesDocument' => 'Quem rêve o documento',
    'DocumentReview' => 'Revisão do documento',
    'DocumentReview_txt' => 'Não selecionou ninguém para rever este documento. O documento será aprovado automáticamente.',
    'DeleteFile' => '<p>Tem a certeza que deseja apagar o <b>Ficheiro</b>?</p>',
    'AllVersions' => 'Todas as versões',
    'Achievement' => 'Realização',
    'Review' => 'Revisão',
    'Disapprove' => 'Reprovar',
    'Approve' => 'Aprovar',
    'ApprovalDocument' => 'Aprovação do documento',
    'ApprovalDocument_txt' => 'Tem a certeza que deseja aprovar este <b>documento</b>?',
    'DocumentDisapproval' => 'Reprovação do documento',
    'DocumentDisapproval_txt' => 'Tem a certeza que deseja reprovar este <b>documento</b>?',
    'Notes' => 'Notas',
    'FileInApproval' => 'Ficheiro em aprovação',
    'ReprovedAproved_txt' => 'Se pretender pode adicionar ficheiros ou escrever uma nota',
    'DocumentInApproval' => 'Documento em aprovação',
    'ApprovedDocument' => 'Documento aprovado',
    'ShareLinkDocument_txt' => 'Partilhe o link com a pessoa que irá rever o documento',


    /*documentos - emails*/
    'TheVersion' => 'A versão',
    'TheVersion_txt' => 'do documento, necessita de revisão. Aceda a sua área reservada e proceda a revisão.',

    /*permissoes*/
    'PERMISSION_TXT_doc_rever' => 'Rever documentos',
    'PERMISSION_TXT_doc_criar' => 'Criação de documentos',
    'PERMISSION_TXT_cert_criar' => 'Criação de certificações',
    'PERMISSION_TXT_gest_documental' => 'Acesso a gestão documental',


    /*
    |--------------------------------------------------------------------------
    | Informação
    |--------------------------------------------------------------------------
    */
    'dashboardTt' => 'Painel',
    'dashboardTx' => 'Aqui são apresentadas as últimas actualizações.',






];
