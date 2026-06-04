import { Link } from "react-router-dom";

function InscriptionRow({ inscription }) {
  const { id, editions, region, province, city, text, min_year, max_year } = inscription;

  const truncate = (testo, maxLength) => {
    if (testo.length <= maxLength) return testo;
    return testo.slice(0, maxLength) + "...";
  };

  return (
    <tr>
      <th scope="row">{id}</th>
      <td>
        <ul>
          {editions.map((edition, i) => (
            <li key={i}>
              {edition.corpus} {edition.volume} {edition.number_inscription}
              {edition.last_name_author}
              {edition.publication_year && ` (${edition.publication_year})`}
              {edition.corpus_page && `, p. ${edition.corpus_page}`}
              {!edition.corpus && "No edition"}
            </li>
          ))}
        </ul>
      </td>
      <td>
        {region}, {province}
        {city && `, ${city}`}
      </td>
      <td>{truncate(text, 100)}</td>
      <td style={{ whiteSpace: "nowrap" }}>
        {min_year} - {max_year}
      </td>
      <td>
        <Link className="btn btn-primary btn-sm text-white" to={`/filings/${id}`}>
          Show details
        </Link>
      </td>
    </tr>
  );
}

export default InscriptionRow;