import * as ReactPaginateModule from "react-paginate";
import InscriptionRow from "./InscriptionRow";

const ReactPaginate =
    ReactPaginateModule.default?.default ||
    ReactPaginateModule.default ||
    ReactPaginateModule.ReactPaginate ||
    ReactPaginateModule;

function PaginatedItems({ inscriptions = [], currentPage = 1, pageCount = 1, loading = false, onPageChange = () => {} }) {

    return (
        <>
            <table className="table table-striped-columns mt-5">
                <thead>
                    <tr className="text-center">
                        <th>ID</th>
                        <th>Bibliografia</th>
                        <th>Origine</th>
                        <th>Testo</th>
                        <th>Data</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    {loading ? (
                        <tr>
                            <td colSpan={6} className="text-center py-4">
                                Caricamento in corso...
                            </td>
                        </tr>
                    ) : inscriptions.length === 0 ? (
                        <tr>
                            <td colSpan={6} className="text-center py-4">
                                Nessuna iscrizione trovata.
                            </td>
                        </tr>
                    ) : (
                        inscriptions.map((inscription) => (
                            <InscriptionRow key={inscription.id} inscription={inscription} />
                        ))
                    )}
                </tbody>
            </table>
            <ReactPaginate
                pageCount={Math.max(pageCount, 1)}
                forcePage={currentPage - 1}
                onPageChange={(event) => onPageChange(event.selected + 1)}
                previousLabel="Previous"
                nextLabel="Next"
                breakLabel="..."
                marginPagesDisplayed={1}
                pageRangeDisplayed={5}
                containerClassName="pagination justify-content-center"
                pageClassName="page-item"
                pageLinkClassName="page-link"
                previousClassName="page-item"
                previousLinkClassName="page-link"
                nextClassName="page-item"
                nextLinkClassName="page-link"
                breakClassName="page-item"
                breakLinkClassName="page-link"
                activeClassName="active"
                disabledClassName="disabled"
            />
        </>
    );
}

export default PaginatedItems;