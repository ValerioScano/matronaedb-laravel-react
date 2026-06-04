import { Link } from "react-router-dom"
function Homepage() {
    return <>
        <div className="d-flex justify-content-center align-items-center flex-column px-5">
            <h1>Benvenuta/o in MatronaeDB</h1>
        </div>
        <div className="mt-5 d-flex justify-content-center align-items-center flex-column">
            <p className="mx-4 text-center">Iscrizioni di evergetismo civico femminile nelle province dell'Impero dall'alta età imperiale fino all'affermazione del cristianesimo</p>
            <p className="mx-4 text-center">Un progetto pensato per Alice Cicarelli, ideato da Valerio Scano</p>
            <Link to="/filings" className="btn btn-outline-primary">Consulta il database</Link>
        </div>


    </>
}

export default Homepage
