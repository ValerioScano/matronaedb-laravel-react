Ecco il README aggiornato:

```markdown
# MatronaeDB

Database di iscrizioni di evergetismo civico femminile nelle province dell'Impero Romano, dall'alta età imperiale fino all'affermazione del cristianesimo.

Un progetto pensato per Alice Cicarelli, ideato da Valerio Scano.

---

## Stack tecnologico

- **Backend**: Laravel 11 (PHP)
- **Frontend**: React (JavaScript)
- **Database**: MySQL
- **Autenticazione**: Laravel Breeze

---

## Ruoli utente

| Ruolo | Descrizione |
|-------|-------------|
| Guest | Utente non registrato — solo consultazione |
| Registered User | Utente registrato — può proporre schedature |
| Admin | Amministratore — approva/rifiuta schedature |

---

## Permessi per ruolo

### Guest
- Consultare il database — solo filings approvati
- Visualizzare il singolo filing
- Applicare filtri di ricerca (testo, tag, corpus, date)

### Registered User
- Tutti i permessi del Guest
- Creare una nuova schedatura (proposal) 
- Modificare una propria proposal in stato `pending`
- Eliminare una propria proposal in stato `pending`
- Proporre una revisione di un filing approvato (proposal modifying of existing filing)
- Visualizzare le proprie proposal (pending, approved, rejected)

### Admin
- Tutti i permessi del Registered User
- Visualizzare tutte le proposal in stato `pending`
- Approvare una proposal → crea o aggiorna il filing corrispondente
- Rifiutare una proposal con note di revisione
- Eliminare un filing (soft delete — recuperabile)

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
| timestamps | | |

### filings
| Campo | Tipo | Note |
|-------|------|------|
| id | bigint | PK |
| text | text | |
| region | string | |
| province | string | |
| city | string | nullable |
| min_year | integer | |
| max_year | integer | |
| is_certain_date | boolean | default false |
| is_sacred_dedication | boolean | default false |
| notes | text | nullable |
| religion | enum | 'uncertain', 'pagan', 'christian' |
| proposed_by | fk | → users.id |
| approved_by | fk | → users.id |
| deleted_at | timestamp | soft delete |
| timestamps | | |

### proposals
| Campo | Tipo | Note |
|-------|------|------|
| id | bigint | PK |
| filing_id | fk | → filings.id, nullable |
| text | text | |
| region | string | |
| province | string | |
| city | string | nullable |
| min_year | integer | |
| max_year | integer | |
| is_certain_date | boolean | default false |
| is_sacred_dedication | boolean | default false |
| notes | text | nullable |
| religion | enum | 'uncertain', 'pagan', 'christian', default 'uncertain' |
| status | enum | 'pending', 'approved', 'rejected' |
| rejection_notes | text | nullable |
| proposed_by | fk | → users.id |
| approved_by | fk | → users.id, nullable |
| deleted_at | timestamp | soft delete |
| timestamps | | |

### editions
| Campo | Tipo | Note |
|-------|------|------|
| id | bigint | PK |
| corpus | string | |
| volume | string | nullable |
| number_inscription | integer | nullable |
| publication_year | integer | nullable |
| corpus_page | integer | nullable |
| last_name_author | string | nullable |
| editionable_id | bigint | FK polimorfica |
| editionable_type | string | 'App\Models\Filing' o 'App\Models\Proposal' |
| timestamps | | |

### tags
| Campo | Tipo | Note |
|-------|------|------|
| id | bigint | PK |
| name | string | chiave tecnica |
| label | string | versione leggibile |
| category | string | categoria |
| timestamps | | |

### taggables
| Campo | Tipo | Note |
|-------|------|------|
| tag_id | fk | → tags.id |
| taggable_id | bigint | FK polimorfica |
| taggable_type | string | 'App\Models\Filing' o 'App\Models\Proposal' |
| timestamps | | |
---

## Ciclo di vita di una proposal

---

Registered User crea proposal
        ↓
        ↓
    Admin revisiona
       ↙        ↘
 Approva            Rifiuta
    ↓                   ↓
Dati copiati        status: rejected
in filings              ↓
status: approved    Visibile solo
                    al proponente
                    (modificabile)

---

## Ciclo di vita di una revisione

---

Registered User propone revisione su filing approved
            ↓
Proposal salvata con filing_id valorizzato (status: pending)
Filing originale rimane visibile a tutti
            ↓
    Admin revisiona
       ↙            ↘
 Approva            Rifiuta
    ↓                   ↓
Filing aggiornato  status: rejected
con dati proposal       ↓
                    Visibile solo
                    al proponente
                    (modificabile)


---

## Rotte principali

| Metodo | Rotta | Controller | Accesso |
|--------|-------|------------|---------|
| GET | / | PublicPageController@welcome | tutti |
| GET | /filings | PublicPageController@index | tutti |
| GET | /filings/{id} | PublicPageController@show | tutti |
| GET | /filings/create | FilingController@create | auth |
| POST | /filings | FilingController@store | auth |
| GET | /filings/{id}/edit | FilingController@edit | auth + proprietario |
| PUT | /filings/{id} | FilingController@update | auth + proprietario |
| DELETE | /filings/{id} | FilingController@destroy | auth + proprietario |
| GET | /filings/{id}/revisions/create | RevisionController@create | auth |
| POST | /filings/{id}/revisions | RevisionController@store | auth |
| GET | /dashboard | FilingController@dashboard | auth |
| GET | /admin/dashboard | AdminController@dashboard | admin |
| GET | /admin/filings/pending | AdminController@pendingFilings | admin |
| PATCH | /admin/filings/{id}/approve | AdminController@approveFiling | admin |
| PATCH | /admin/filings/{id}/reject | AdminController@rejectFiling | admin |
| GET | /admin/proposals/pending | AdminController@pendingProposals | admin |
| PATCH | /admin/proposals/{id}/approve | AdminController@approveProposal | admin |
| PATCH | /admin/proposals/{id}/reject | AdminController@rejectProposal | admin |

---

## Controllers

| Controller | Responsabilità |
|------------|----------------|
| PublicPageController | Pagine pubbliche |
| FilingController | CRUD schedature |
| RevisionController | CRUD revisioni |
| AdminController | Approvazioni e dashboard admin |
| ProfileController | Gestione profilo (Breeze) |

---