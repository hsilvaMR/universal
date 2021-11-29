<ul class="nav-menu">

    <li class="nav-item-cabecalho">
        <a href="{{ route('adminAccountPageB') }}">
            <div id="avatarNav" class="nav-avatar" @if(json_decode(\Cookie::get('admin_cookie'))->avatar)
                style="background-image:url('/backoffice/img/admin/{{ json_decode(\Cookie::get('admin_cookie'))->avatar }}');"
                @endif></div>
            <span id="nameNav">{{ json_decode(\Cookie::get('admin_cookie'))->nome }}</span>
        </a>
    </li>

    @if(json_decode(\Cookie::get('admin_cookie'))->tipo == 'admin')
    <li class="nav-item-principal @if($separador=='dashboard') active @endif">
        <a href="{{ route('dashboardPageB') }}">
            <span class="fas fa-tachometer-alt"></span>
            {{ trans('backoffice.Dashboard') }}
            <span class="selected"></span>
        </a>
    </li>

    @php
    $array=['webCheeseQuestions','webCheeseAwards','webCheeseFAQs','webGiveaways','webSlide','webContacts','webSlide_colors','culinaria','culinaria_enc'];
    @endphp
    <li class="nav-item-principal @if(in_array($separador, $array)) open @endif">
        <a href="javascript:;" class="menuClick">
            <span class="fas fa-desktop"></span>
            {{ trans('backoffice.Website') }}
            <i class="fas fa-angle-down @if(in_array($separador, $array)) rodar180 @endif"></i>
        </a>
        <ul class="nav-sub-menu menuOpen">
            @php $array=['webCheeseQuestions','webCheeseAwards','webCheeseFAQs']; @endphp
            <li class="nav-item-secundario @if(in_array($separador, $array)) open @endif">
                <a href="javascript:;" class="menuClickSub">
                    {{ trans('backoffice.littleCheese') }}
                    <i class="fas fa-angle-down angle-sub @if(in_array($separador, $array)) rodar180 @endif"></i>
                </a>
                <ul class="nav-sub-sub-menu menuOpenSub">
                    <li class="nav-item-terciario @if($separador=='webCheeseQuestions') active @endif">
                        <a href="{{ route('cheeseQuestionsAllPageB') }}">
                            {{ trans('backoffice.Questions') }}
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item-terciario @if($separador=='webCheeseAwards') active @endif">
                        <a href="{{ route('cheeseAwardsAllPageB') }}">
                            {{ trans('backoffice.Awards') }}
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item-terciario @if($separador=='webCheeseFAQs') active @endif">
                        <a href="{{ route('cheeseFAQsAllPageB') }}">
                            {{ trans('backoffice.FAQs') }}
                            <span class="selected"></span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item-secundario @if($separador=='webGiveaways') active @endif">
                <a href="{{ route('giveawaysAllPageB') }}">
                    {{ trans('backoffice.Giveaways') }}
                    <span class="selected"></span>
                </a>
            </li>

            @php $array=['webSlide','webSlide_colors']; @endphp
            <li class="nav-item-secundario @if(in_array($separador, $array)) open @endif">
                <a href="javascript:;" class="menuClickSub">
                    {{ trans('backoffice.Slide') }}
                    <i class="fas fa-angle-down @if(in_array($separador, $array)) rodar180 @endif"></i>
                </a>

                <ul class="nav-sub-sub-menu menuOpenSub">
                    <li class="nav-item-terciario @if($separador=='webSlide') active @endif">
                        <a href="{{ route('webSlideAllPageB') }}">
                            Todos os slides
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item-terciario @if($separador=='webSlide_colors') active @endif">
                        <a href="{{ route('colorsPageB') }}">
                            Cores
                            <span class="selected"></span>
                        </a>
                    </li>

                </ul>
            </li>

            @php $array=['culinaria','culinaria_enc']; @endphp
            <li class="nav-item-secundario @if(in_array($separador, $array)) open @endif">
                <a href="javascript:;" class="menuClickSub">
                    Culinária
                    <i class="fas fa-angle-down @if(in_array($separador, $array)) rodar180 @endif"></i>
                </a>

                <ul class="nav-sub-sub-menu menuOpenSub">
                    <li class="nav-item-terciario @if($separador=='culinaria') active @endif">
                        <a href="{{ route('cookingPageB') }}">
                            Códigos Promocionais
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item-terciario @if($separador=='culinaria_enc') active @endif">
                        <a href="{{ route('cookingOrdersPageB') }}">
                            Encomendas
                            <span class="selected"></span>
                        </a>
                    </li>

                </ul>
            </li>

            <li class="nav-item-secundario @if($separador=='webContacts') active @endif">
                <a href="{{ route('contactsAllPageB') }}">
                    {{ trans('backoffice.Contacts') }}
                    <span class="selected"></span>
                </a>
            </li>
        </ul>
    </li>

    {{--  area de utilizador  --}}
    @php $array=['userUsers','userSettings','userAwards']; @endphp
    <li class="nav-item-principal @if(in_array($separador, $array)) open @endif">
        <a href="javascript:;" class="menuClick">
            <span class="fas fa-user"></span>
            {{ trans('backoffice.UserArea') }}
            <i class="fas fa-angle-down @if(in_array($separador, $array)) rodar180 @endif"></i>
        </a>
        <ul class="nav-sub-menu menuOpen">
            <li class="nav-item-secundario @if($separador=='userUsers') active @endif">
                <a href="{{ route('usersPageB') }}">
                    {{ trans('backoffice.Users') }}
                    <span class="selected"></span>
                </a>
            </li>

            <li class="nav-item-secundario @if($separador=='userAwards') active @endif">
                <a href="{{ route('allUserAwardsPageB') }}">
                    {{ trans('backoffice.Awards') }}
                    <span class="selected"></span>
                </a>
            </li>
        </ul>
    </li>

    {{--  area  comercial   --}}
    @php
    $array=['bizCompanies','bizSellers','bizAdresses','bizProduts','bizAwards','bizOrders','bizInformation','sellerAwards','sellerComuni'];
    @endphp
    <li class="nav-item-principal @if(in_array($separador, $array)) open @endif">
        <a href="javascript:;" class="menuClick">
            <span class="fas fa-briefcase"></span>
            {{ trans('backoffice.CommercialArea') }}
            <i class="fas fa-angle-down @if(in_array($separador, $array)) rodar180 @endif"></i>
        </a>
        <ul class="nav-sub-menu menuOpen">
            <li class="nav-item-secundario @if($separador=='bizCompanies') active @endif">
                <a href="{{ route('companiesPageB') }}">
                    {{ trans('backoffice.Companies') }}
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item-secundario @if($separador=='bizSellers') active @endif">
                <a href="{{ route('sellersPageB') }}">
                    {{ trans('backoffice.Sellers') }}
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item-secundario @if($separador=='bizOrders') active @endif">
                <a href="{{ route('ordersAllPageB') }}">
                    {{ trans('backoffice.Orders') }}
                    <span class="selected"></span>
                </a>
            </li>

            <li class="nav-item-secundario @if($separador=='sellerAwards') active @endif">
                <a href="{{ route('allSellerAwardsPageB') }}">
                    {{ trans('backoffice.Awards') }}
                    <span class="selected"></span>
                </a>
            </li>

            {{--  comuicação  --}}
            <li class="nav-item-secundario @if($separador=='sellerAwards') active @endif">
                <a href="{{ route('allSellerAwardsPageB') }}">
                    {{ trans('backoffice.communication') }}
                    <span class="selected"></span>
                </a>
            </li>

        </ul>
    </li>
    @endif

    @php
    $array2=[];
    foreach(json_decode(\Cookie::get('permissions_cookie')) as $value){

    array_push($array2, $value->tipo);
    }

    if(in_array('gest_documental', $array2)) {
    $tipo_gest_doc = 'sim';
    }
    @endphp

    @if(isset($tipo_gest_doc) || (json_decode(\Cookie::get('admin_cookie'))->tipo == 'visualizador'))
    @if(\Cookie::get('certifications_cookie') && json_decode(\Cookie::get('certifications_cookie')))
    @php
    $array=[];
    foreach(json_decode(\Cookie::get('certifications_cookie')) as $value){
    $aux = 'gestViewer'.$value->id;
    array_push($array, $aux);
    }
    @endphp
    <li class="nav-item-principal @if(in_array($separador, $array)) open @endif">
        <a href="javascript:;" class="menuClick">
            <span class="fas fa-tasks"></span>
            {{ trans('backoffice.DocumentManagement') }}
            <i class="fas fa-angle-down @if(in_array($separador, $array)) rodar180 @endif"></i>
        </a>
        <ul class="nav-sub-menu menuOpen">
            @foreach(json_decode(\Cookie::get('certifications_cookie')) as $value)
            <li class="nav-item-secundario @if($separador=='gestViewer'.$value->id) active @endif">
                <a href="{{ route('certificationsIdPageB',$value->id) }}">
                    {{ $value->nome }}
                    <span class="selected"></span>
                </a>
            </li>
            @endforeach
        </ul>
    </li>
    @endif
    @endif

    @if(json_decode(\Cookie::get('admin_cookie'))->tipo != 'visualizador')
    @php
    $array=['setAwards','setVariants','setLabels','setProducts','setTechnical','setSettings','setGestCertifications','setGestDocuments','setGestAcronyms'];
    @endphp
    <li class="nav-item-principal @if(in_array($separador, $array)) open @endif">
        <a href="javascript:;" class="menuClick">
            <span class="fas fa-cog"></span>
            {{ trans('backoffice.Settings') }}
            <i class="fas fa-angle-down @if(in_array($separador, $array)) rodar180 @endif"></i>
        </a>
        <ul class="nav-sub-menu menuOpen">
            @if(json_decode(\Cookie::get('admin_cookie'))->tipo == 'admin')
            @php $array=['setAwards','setVariants']; @endphp
            <li class="nav-item-secundario @if(in_array($separador, $array)) open @endif">
                <a href="javascript:;" class="menuClickSub">
                    {{ trans('backoffice.Awards') }}
                    <i class="fas fa-angle-down angle-sub @if(in_array($separador, $array)) rodar180 @endif"></i>
                </a>
                <ul class="nav-sub-sub-menu menuOpenSub">
                    <li class="nav-item-terciario @if($separador=='setAwards') active @endif">
                        <a href="{{ route('awardsAllPageB') }}">
                            {{ trans('backoffice.allAwards') }}
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item-terciario @if($separador=='setVariants') active @endif">
                        <a href="{{ route('awardsVariantsAllPageB') }}">
                            {{ trans('backoffice.Variants') }}
                            <span class="selected"></span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item-secundario @if($separador=='setLabels') active @endif">
                <a href="{{ route('labelsPageB') }}">
                    {{ trans('backoffice.Labels') }}
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item-secundario @if($separador=='setProducts') active @endif">
                <a href="{{ route('productsAllPageB') }}">
                    {{ trans('backoffice.Products') }}
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item-secundario @if($separador=='setTechnical') active @endif">
                <a href="{{ route('techInfoPageB') }}">
                    {{ trans('backoffice.TechnicalInformation') }}
                    <span class="selected"></span>
                </a>
            </li>
            <li class="nav-item-secundario @if($separador=='setSettings') active @endif">
                <a href="{{ route('settingsPageB') }}">
                    {{ trans('backoffice.Definitions') }}
                    <span class="selected"></span>
                </a>
            </li>
            @endif

            @php $array=['setGestCertifications','setGestDocuments','setGestAcronyms']; @endphp
            <li class="nav-item-secundario @if(in_array($separador, $array)) open @endif">
                <a href="javascript:;" class="menuClickSub">
                    {{ trans('backoffice.DocumentManagement') }}
                    <i class="fas fa-angle-down angle-sub @if(in_array($separador, $array)) rodar180 @endif"></i>
                </a>
                <ul class="nav-sub-sub-menu menuOpenSub">

                    <li class="nav-item-secundario @if($separador=='setGestCertifications') active @endif">
                        <a href="{{ route('certificationsPageB') }}">
                            {{ trans('backoffice.Certifications') }}
                            <span class="selected"></span>
                        </a>
                    </li>

                    <li class="nav-item-secundario @if($separador=='setGestDocuments') active @endif">
                        <a href="{{ route('managementDocPageB') }}">
                            {{ trans('backoffice.Documents') }}
                            <span class="selected"></span>
                        </a>
                    </li>
                    <li class="nav-item-terciario @if($separador=='setGestAcronyms') active @endif">
                        <a href="{{ route('acronymsPageB') }}">
                            {{ trans('backoffice.Acronyms') }}
                            <span class="selected"></span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>
    @endif
</ul>