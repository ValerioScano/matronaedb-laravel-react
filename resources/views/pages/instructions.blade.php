@extends('layouts.app')

@section('content')
<div class="row text-center flex-column py-5">
            <div class="col-12 mb-5">
                <h1>Instructions on how to use the database</h1>
            </div>

            <div class="col-12 text-start">
                <h3>What's MatronaeDB</h3>
                    <p>MatronaeDB è un database che raccoglie iscrizioni di età imperiale e tardoantica 
                        di evergetismo femminile nelle province occidentali dell'Impero Romano. 
                        Il progetto nasce open-source e gratuitamente accessibile per tutti gli studiosi che vogliano consultare il database 
                        o contribuire ad ampliarlo</p>
                <h3>What do we consider as evergetism?</h3>
                <p>Criteria are currently being determined by Alice Cicarelli in her doctoral project</p>
                <h3>How to consult the database</h3>
                <p>Non è necessario registrarsi per consultare il database. I "filings" sono  schedature proposte tramite form
                     dagli utenti registrati che sono state poi approvate da un admin. Su richiesta si può divenire admin
                      per collaborare al processo di schedatura.</p>
                <p>I "filings" possono essere filtrati sulla base del testo, dei tag assegnati loro, luogo di provenienza,
                     datazione e altri dati relativi sia alle iscrizioni, sia alle loro edizioni.</p>
                <p>I tag con "?" indicano incertezza.</p>
                <h3>How to propose a filing</h3>
                <p>Tutti gli utenti registrati possono proporre una nuova schedatura oppure proporre una revisione 
                    a una schedatura precedemente approvata. Tutte le proposte di schedatura devono poi essere approvate dagli 
                admin per divenire "filings".</p>
                <p>
                    <em>Viene utilizzato il font IFAO-grec unicode per rendere tutti i segni di lettura. Chi consulta il database è invitato, 
                        per un corretto funzionamento, ad impiegare tale font. Per praticità è stato inserito un tastierino nei form di schedatura per agevolare 
                    l'inserimento dei simboli più frequenti.</em>
                </p>

                <h3>Abbreviations' standards</h3>
                <p>We use EDR list of abbreviations, that you can find  <a href="http://www.edr-edr.it/it/Guida_coll_it.php" class="btn btn-sm btn-outline-dark">here</a>.</p>
                
                    
            </div>
        </div>

@endsection