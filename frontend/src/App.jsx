import { BrowserRouter, Routes, Route } from 'react-router-dom'
import DefaultLayout from './layouts/DefaultLayout'
import Homepage from './pages/Homepage'
import InscriptionPortalPage from './pages/InscriptionPortalPage'
import NotFoundPage from './pages/NotFoundPage'
import DetailPage from './pages/DetailPage'
import './App.css'

function App() {
  // const [count, setCount] = useState(0)

  return (
    <BrowserRouter>
      <Routes>
        <Route element={<DefaultLayout></DefaultLayout>}>
          <Route path="/" element={<Homepage></Homepage>}></Route>
          <Route path="/filings" element={<InscriptionPortalPage></InscriptionPortalPage>}></Route>
          <Route path="/filings/:id" element={<DetailPage></DetailPage>}></Route>
          <Route path="*" element={<NotFoundPage></NotFoundPage>}></Route>
        </Route>
      </Routes>
    </BrowserRouter>


  )
}

export default App