import { useState, useEffect } from "react";
import axios from "axios";
import PaginatedItems from "../components/PaginatedItems";

function InscriptionPortalPage() {
  const [inscriptions, setInscriptions] = useState([]);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    setLoading(true);
    axios
      .get(`http://127.0.0.1:8000/api/filings?page=${currentPage}`)
      .then((response) => {
        setInscriptions(response.data.data ?? []);
        setTotalPages(response.data.last_page ?? 1);
      })
      .catch((err) => console.error("errore", err.message))
      .finally(() => setLoading(false));
  }, [currentPage]);

  return (
    <div className="container">
      <div className="row d-flex justify-content-center">
        <div className="col-12 text-center">
          <h1>Pagina per consultare il DB</h1>
        </div>
      </div>
      <div className="row px-5 mb-5">
        <PaginatedItems
          inscriptions={inscriptions}
          currentPage={currentPage}
          pageCount={totalPages}
          loading={loading}
          onPageChange={(page) => setCurrentPage(page)}
        />
      </div>
    </div>
  );
}

export default InscriptionPortalPage;