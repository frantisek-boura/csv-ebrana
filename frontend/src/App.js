import { useState, useEffect } from 'react'
import axios from "axios"

const App = () => {

    const [data, setData] = useState([]) 
    const [uploading, setUploading] = useState(false)
    const [uploadMessage, setUploadMessage] = useState("")
    const [loading, setLoading] = useState(true)
    const [connectionError, setConnectionError] = useState(false)

    const loadData = () => {
        setLoading(true)

        axios.get("http://localhost:8000/api/items")
        .then((response) => {
            setData(response.data)
            setConnectionError(false)
        }).catch(() => {
            setConnectionError(true)
        }).finally(() => {
            setLoading(false)
        })
    }

    const submitFile = (e) => {
        e.preventDefault()

        let formData = new FormData(e.target)
        let file = formData.get('file')

        if (!file) {
            console.error('Incorrect file')
            return
        } 

        setUploading(true)
        axios.post("http://localhost:8000/api/items", formData, { 
            headers: {
                'Content-Type': 'multipart/form-data'
            } 
        }).then(() => {
            setUploadMessage("Data uploaded successfully");
            setConnectionError(false)
        }).catch(() => {
            setUploadMessage("There was an error while uploading the data");
            setConnectionError(true)
        }).finally(() => {
            setUploading(false)
            loadData()
        })

    }

    useEffect(() => {
        if (uploadMessage.length > 0) {
            const timeout = setTimeout(() => {
                setUploadMessage("")
            }, 5000);

            return () => clearTimeout(timeout);
        }
    }, [uploadMessage])

    useEffect(() => { 
        loadData()
    }, [])

    return (
        <>
            <h1>Produkty</h1>
            <form onSubmit={submitFile}>
                <input type='file' name='file' accept='text/csv' />
                <button type='submit'>Nahrát data</button>
            </form>
            {connectionError && <h3>Nelze navázat spojení se serverem</h3>}
            {(!connectionError && uploading) && <h3>Nahrávání...</h3>}
            {(!connectionError && uploadMessage.length > 0) && <h3>{uploadMessage}</h3>}
            {(!connectionError && loading && !uploading) && <h3>Načítání...</h3>}
            {(!connectionError && data.length == 0 && !uploading && !loading) && <h3>Nejsou nahraná žádná data</h3>}
            {(!connectionError && data.length > 0 && !uploading && !loading) && 
                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>EAN13</th>
                            <th>DUN14</th>
                            <th>Carton_Qty</th>
                            <th>Price</th>
                            <th>Weight(kg)</th>
                            <th>Size</th>
                            <th>Colour Name</th>
                            <th>Image</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        {data.map((item, index) => {
                            return (
                                <tr key={index}>
                                    <td>{item.code}</td>
                                    <td>{item.ean13}</td>
                                    <td>{item.dun14}</td>
                                    <td>{item.cartonQty}</td>
                                    <td>{item.price}</td>
                                    <td>{item.weight}</td>
                                    <td>{item.size}</td>
                                    <td>{item.colour}</td>
                                    <td><img src={item.imagePath} width={100} height={100} alt={item.imagePath}/></td>
                                    <td>{item.description}</td>
                                </tr>
                            )
                        })}
                    </tbody>
                </table>
            }
        </>
    )

}

export default App