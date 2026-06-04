import { useParams } from "react-router-dom";
import axios from "axios";
import { useState, useEffect } from "react";
import style from "./DetailPage.module.css";
import { Link } from "react-router-dom";
import { useNavigate } from "react-router-dom";

function DetailPage() {
  const navigate = useNavigate();

  const { id } = useParams();
  const [inscription, setInscription] = useState({ tags: [], editions: [{}] });
  useEffect(() => {
    axios
      .get(`http://127.0.0.1:8000/api/filings/${id}`)
      .then((response) => {
        console.log(response.data);
        setInscription(response.data.data);
      })
      .catch((err) => {
        console.error("errore", err.message);
        navigate("/");
      });
  }, [id]);

  return (
    <>
      <div className="d-flex justify-content-between align-items-center px-5">
        <button type="button" className="btn btn-outline-primary ">
          <Link to={`/filings/${Number(id) - 1}`}>
            Previous
          </Link>
        </button>
        <div>
          <h1>Inscription id: {inscription?.id}</h1>
          <p className="text-center">
            {inscription?.min_year} - {inscription?.max_year}
            {!inscription?.is_certain_date ? ", unsure date" : ""}
          </p>
          <p className="text-center"> Religion {inscription?.religion}</p>
        </div>
        <button type="button" className={"btn btn-outline-primary "}>
          <Link to={`/filings/${Number(id) + 1}`}>
            Next
          </Link>
        </button>
      </div>

      <div className="container mt-5">
        <div className="row mb-5 gb-5 p-3 border border-primary-subtle rounded">
          <div className="col-4 text-start">
            <ul className={style.list}>
              <h3>Editions:</h3>
              {inscription.editions?.map((edition, i) => {
                return (
                  <li key={i}>
                    {edition.corpus} {edition.volume}{" "}
                    {edition.number_inscription}
                    {edition.last_name_author && `, ${edition.last_name_author}`}
                    {edition.publication_year &&
                      ` (${edition.publication_year})`}
                    {edition.corpus_page && `, p. ${edition.corpus_page}`}
                    ;
                  </li>
                );
              })}
            </ul>
          </div>
          <div className="col-8 text-center">
            <h3>Text:</h3>
            {inscription?.text}
          </div>
        </div>

        <div className="row mb-5 gb-5 p-3 text-center border border-primary-subtle rounded ">
          <div className="col-3">
            <ul className={style.list}>
              {" "}
              <h5>Vocabulary:</h5>
              {inscription.tags?.filter(tag => tag.category === 'vocabulary').map((tag, i) => (
                <li key={i}>{tag.label}</li>
              ))}
            </ul>
          </div>
          <div className="col-3">
            <ul className={style.list}>
              {" "}
              <h5>Role:</h5>
              {inscription.tags?.filter(tag => tag.category === 'role').map((tag, i) => (
                <li key={i}>{tag.label}</li>
              ))}
            </ul>
          </div>
          <div className="col-3">
            <ul className={style.list}>
              {" "}
              <h5>Agency:</h5>
              {inscription.tags?.filter(tag => tag.category === 'agency').map((tag, i) => (
                <li key={i}>{tag.label}</li>
              ))}
            </ul>
          </div>

        </div>
        <div className="row mb-5 gb-5 p-3 text-center border border-primary-subtle rounded ">
          <div className="col-3">
            <h4>Note:</h4>
          </div>
          <div className="col-9">
            <p>{inscription?.note}</p>
          </div>
        </div>
      </div>
    </>
  );
}

export default DetailPage;
