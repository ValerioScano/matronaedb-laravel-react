import style from "../components/Header.module.css";
import { Link, NavLink } from "react-router-dom";

function Header() {
  return (
    <div className="d-flex justify-content-between align-items-center p-3 mb-3 sticky-top bg-secondary-subtle">
      <div className={style.container_logo}>
        <Link to="/">
          <img src="/logo.png" alt="logo MatronaeDB" className={style.logo} />
        </Link>
      </div>

      <div className="d-flex gap-3">
        <NavLink className="btn" to="/">
          Homepage
        </NavLink>
        <NavLink className="btn" to="/filings">
          Portale iscrizioni
        </NavLink>
        <a href="http://127.0.0.1:8000/login" className="btn btn-outline-secondary">
          Login
        </a>
      </div>
    </div>
  );
}

export default Header;
