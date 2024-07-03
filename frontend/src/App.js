import { useState, useEffect } from 'react'
import axios from "axios"

const App = () => {

    const [data, setData] = useState([]) 
    const [uploading, setUploading] = useState(false)
    const [uploadMessage, setUploadMessage] = useState("")
    const [loading, setLoading] = useState(true)
    const [connectionError, setConnectionError] = useState(true)

    const submitFile = (e) => {
        e.preventDefault()

        let formData = new FormData(e.target)
        let file = formData.get('file')

        if (!file) console.error('Incorrect file')

        setUploading(true)
        axios.post("localhost:8000/api/items", formData, { 
            headers: {
                'Content-Type': 'multipart/form-data'
            } 
        }).then(() => {
            setUploadMessage("Data uploaded successfully");
        }).catch(() => {
            setUploadMessage("There was an error while uploading the data");
            setConnectionError(true)
        })

        setUploading(false)
    }

    useEffect(() => {
        setTimeout(() => {
            if (uploadMessage.length > 0) setUploadMessage("")
        }, 5000);
    }, [uploadMessage])

    useEffect(() => {
    
        setLoading(true)
        axios.get("http://localhost:8000/api/items")
        .then((response) => {
            setData(response.data)
            console.log(response.data)
            setConnectionError(false)
            setLoading(false)
        }).catch(() => {
            setData([])
            setConnectionError(true)
            setLoading(false)
        });

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
            {(!connectionError && loading) && <h3>Načítání...</h3>}
            {(!connectionError && data.length == 0) && <h3>Nejsou nahraná žádná data</h3>}
            {(!connectionError && data.length > 0) && 
                <table>
                    <th>
                        <td>Code</td>
                        <td>EAN13</td>
                        <td>DUN14</td>
                        <td>Carton_Qty</td>
                        <td>Price</td>
                        <td>Weight(kg)</td>
                        <td>Size</td>
                        <td>Colour Name</td>
                        <td>Image</td>
                        <td>Description</td>
                    </th>
                    {data.map((item, index) => {
                        return (
                            <tr key={index}>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        )
                    })}
                </table>
            }
        </>
    )

}

export default App