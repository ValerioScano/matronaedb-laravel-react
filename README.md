# MatronaeDB

Database di iscrizioni di evergetismo civico femminile nelle province dell'Impero Romano, dall'alta età imperiale fino all'affermazione del cristianesimo.

Un progetto pensato per Alice Cicarelli, ideato da Valerio Scano.

---

## Stack tecnologico

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade + JavaScript vanilla (form dinamici), Bootstrap 5, Vite
- **Database**: MySQL
- **Autenticazione**: Laravel Breeze
- **Mail**: Resend (notifiche di approvazione/rifiuto proposal e cancellazione filing)


---

## Ruoli utente

| Ruolo | Descrizione |
|-------|-------------|
| Guest | Utente non registrato — solo consultazione |
| Registered User | Utente registrato — può proporre schedature |
| Admin | Amministratore — approva/rifiuta schedature, gestisce tag e utenti |

---

## Permessi per ruolo

### Guest
- Consultare il database — tutti i filings pubblicati
- Visualizzare il singolo filing
- Applicare filtri di ricerca (testo full-text con operatori AND/OR/NOT, tag, corpus/volume/numero iscrizione, persone, regione/provincia/città, anni, religione)

### Registered User
- Tutti i permessi del Guest
- Creare una nuova schedatura (proposal)
- Modificare o eliminare una propria proposal (anche se già rifiutata)
- Proporre una revisione di un filing approvato
- Visualizzare le proprie proposal (pending, approved, rejected) con relativa cronologia
- Aggiungere note private alla proposal, visibili anche all'admin durante la revisione

### Admin
- Tutti i permessi del Registered User
- Visualizzare tutte le proposal in stato `pending`
- Approvare una proposal → crea o aggiorna il filing corrispondente, copiando edizioni, persone, risorse esterne e tag; invia mail di approvazione
- Rifiutare una proposal con note di rifiuto; invia mail di rifiuto
- Eliminare un filing con note di cancellazione (soft delete, elimina anche le proposal collegate); invia mail di cancellazione
- Gestire i tag (creazione/modifica/eliminazione in coppie "certo"/"incerto")
- Gestire gli utenti: promuovere/declassare ad admin, eliminare un utente (con o senza i suoi record)

---

## Struttura del database

### users
| Campo | Tipo | Note |
|-------|------|------|
| id | bigint | PK |
| first_name | string | |
| last_name | string | |
| email | string | unique |
| password | string | |
| role | enum | 'registered_user', 'admin' |
| deleted_at | timestamp | soft delete |
| timestamps | | |

### filings
| Campo | Tipo | Note |
|-------|------|------|
| id | bigint | PK |
| text | text | |
| region | string | indexed |
| province | string | indexed |
| city | string | nullable, indexed |
| min_year / max_year | smallint | nullable, indexed |
| is_certain_date | boolean | default false |
| is_sacred_dedication | boolean | default false |
| notes | text | nullable |
| religion | enum | 'uncertain', 'pagan', 'christian' |
| proposed_by | fk | → users.id |
| approved_by | fk | → users.id |
| deletion_notes | text | nullable — motivazione di una cancellazione admin |
| deleted_at | timestamp | soft delete |
| timestamps | | |

### proposals
| Campo | Tipo | Note |
|-------|------|------|
| id | bigint | PK |
| filing_id | fk | → filings.id, nullable (valorizzato solo per le revisioni) |
| text | text | |
| region / province / city | string | city nullable |
| min_year / max_year | smallint | nullable |
| is_certain_date | boolean | default false |
| is_sacred_dedication | boolean | default false |
| notes | text | nullable |
| private_notes | longtext | nullable — note non pubbliche tra proponente e admin |
| religion | enum | 'uncertain', 'pagan', 'christian' |
| status | enum | 'pending', 'approved', 'rejected' |
| rejection_notes | text | nullable |
| proposed_by | fk | → users.id |
| approved_by | fk | → users.id, nullable |
| deleted_at | timestamp | soft delete |
| timestamps | | |

### editions
Collegate polimorficamente a `filings` o `proposals` (`editionable`).

| Campo | Tipo | Note |
|-------|------|------|
| id | bigint | PK |
| corpus | string | |
| volume | string | nullable |
| number_inscription | integer | nullable |
| edition_type | string | nullable |
| publication_year | integer | nullable |
| corpus_page | integer | nullable |
| last_name_author | string | nullable |
| edition_image | string | nullable — path su storage |
| link | string | nullable — link a risorsa esterna (es. EDR, Klaus) |
| editionable_id / editionable_type | | FK polimorfica |
| deleted_at | timestamp | soft delete |
| timestamps | | |

### people
Collegate polimorficamente a `filings` o `proposals` (`peopleable`).

| Campo | Tipo | Note |
|-------|------|------|
| id | bigint | PK |
| praenomen / nomen / cognomen | string | nullable |
| TM_PER_id | string | nullable — riferimento a Trismegistos People |
| peopleable_id / peopleable_type | | FK polimorfica |
| deleted_at | timestamp | soft delete |
| timestamps | | |

### external_resources
Collegate polimorficamente a `filings` o `proposals` (`external_resourceable`).

| Campo | Tipo | Note |
|-------|------|------|
| id | bigint | PK |
| name | string | |
| link | string | |
| external_resourceable_id / external_resourceable_type | | FK polimorfica |
| timestamps | | |

### tags
| Campo | Tipo | Note |
|-------|------|------|
| id | bigint | PK |
| name | string | chiave tecnica (i tag "incerti" hanno lo stesso nome con `?` finale) |
| label | string | versione leggibile |
| category | string | categoria |
| deleted_at | timestamp | soft delete |
| timestamps | | |

### taggables
| Campo | Tipo | Note |
|-------|------|------|
| tag_id | fk | → tags.id |
| taggable_id / taggable_type | | FK polimorfica ('App\Models\Filing' o 'App\Models\Proposal') |
| timestamps | | |

---

## Ciclo di vita di una proposal

```
Registered User crea proposal
        ↓
    Admin revisiona
       ↙        ↘
 Approva            Rifiuta
    ↓                   ↓
Dati copiati        status: rejected
in filings,          (mail di rifiuto)
mail di approvazione      ↓
    ↓                Visibile solo al
status: approved    proponente (modificabile/eliminabile)
```

## Ciclo di vita di una revisione

```
Registered User propone revisione su filing approvato
            ↓
Proposal salvata con filing_id valorizzato (status: pending)
Filing originale rimane visibile a tutti
            ↓
    Admin revisiona
       ↙            ↘
 Approva            Rifiuta
    ↓                   ↓
Filing aggiornato  status: rejected
con dati proposal,  (mail di rifiuto)
mail di approvazione      ↓
                    Visibile solo al
                    proponente (modificabile/eliminabile)
```

## Cancellazione di un filing (admin)

L'admin può eliminare un filing fornendo delle `deletion_notes`. L'operazione è un soft delete: vengono eliminate anche le proposal collegate, edizioni, persone, risorse esterne e tag associati, e viene inviata una mail di notifica al proponente originale.

---

## Rotte principali

| Metodo | Rotta | Controller | Accesso |
|--------|-------|------------|---------|
| GET | / | closure (welcome) | tutti |
| GET | /filings | FilingController@index | tutti |
| GET | /filings/{filing} | FilingController@show | tutti |
| DELETE | /filings/{filing} | FilingController@destroy | admin |
| GET | /proposals | ProposalController@index | auth |
| GET | /proposals/create | ProposalController@create | auth |
| POST | /proposals | ProposalController@store | auth |
| GET | /proposals/pending | ProposalController@pending | admin |
| GET | /proposals/{proposal} | ProposalController@show | auth |
| GET | /proposals/{proposal}/edit | ProposalController@edit | auth |
| PUT | /proposals/{proposal} | ProposalController@update | auth |
| DELETE | /proposals/{proposal} | ProposalController@destroy | auth |
| PATCH | /proposals/{proposal}/approve | ProposalController@approve | admin |
| PATCH | /proposals/{proposal}/reject | ProposalController@reject | admin |
| GET | /proposals/filings/{filing}/create | ProposalController@createRevision | auth |
| POST | /proposals/filings/{filing} | ProposalController@storeRevision | auth |
| GET | /tags | TagController@index | admin |
| GET\|POST | /tags/create, /tags | TagController@create, @store | admin |
| GET\|PUT\|DELETE | /tags/{tag}/edit, /tags/{tag} | TagController@edit, @update, @destroy | admin |
| GET | /users | UserController@index | admin |
| PATCH | /users/{user}/role | UserController@updateRole | admin |
| DELETE | /users/{user}, /users/{user}/with-records | UserController@destroy*, @destroyWithRecords | admin |
| GET | /dashboard | ProfileController@dashboard | auth |
| GET\|PATCH\|DELETE | /profile | ProfileController@edit, @update, @destroy | auth |

Le rotte di autenticazione (login, registrazione, reset password, verifica email) sono gestite da Breeze in `routes/auth.php`.

---

## Controllers

| Controller | Responsabilità |
|------------|----------------|
| FilingController | Listing pubblico con filtri, dettaglio, cancellazione (admin) |
| ProposalController | CRUD proposal, revisioni, approvazione/rifiuto |
| TagController | CRUD coppie di tag certo/incerto |
| UserController | Gestione ruoli e cancellazione utenti |
| ProfileController | Dashboard e gestione profilo (Breeze) |

Middleware `IsAdmin` (alias `admin`) protegge le rotte riservate agli amministratori.

---

## Modelli e relazioni

- `Filing` / `Proposal`: condividono i trait `HasBibliography`, `HasOrigin`, `HasDatation`, `HasTruncate`; hanno relazioni morph-many verso `editions`, `people`, `externalResources` e morph-to-many verso `tags`.
- `Edition`, `Person`, `ExternalResource`: collegati polimorficamente a `Filing` o `Proposal`.
- `Tag`: i tag sono gestiti in coppie "certo"/"incerto" (stesso nome con `?` finale), entrambi creati/aggiornati/eliminati insieme da `TagController`.
- `User`: relazioni verso filings/proposals proposte e approvate; `isAdmin()` per il controllo dei permessi.

---

## Notifiche email

Inviate tramite Resend:

- `ApprovedProposal` — alla proposta approvata
- `RejectedProposal` — alla proposta rifiutata
- `CanceledFiling` — alla cancellazione di un filing da parte dell'admin

Template in `resources/views/pages/emails/`.
