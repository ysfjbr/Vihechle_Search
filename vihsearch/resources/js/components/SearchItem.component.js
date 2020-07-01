import React, {useEffect, useState} from "react"
import RequestApi from "./RequestApi"
//import ShowImage from "../../../components/showImage.compon
//import { Icon } from "semantic-ui-react"
//import Cookies from 'js-cookie';

function getItemData(itemID,callback) {
       
    RequestApi(     "/api/getData",
                    {"itemID":itemID},//{ 'table' : "call" , "filter":{"_id" : itemID}},
                    () => {},
                    result => callback(result.data),
                    error => console.log(error)
                )
} 

export default function SearchItem({itemID}) {

    const [item, setItem] = useState()

    function setData(data)
    {
        console.log(data)
        setItem(data)
    }

    useEffect(()=> {
        getItemData(itemID ,item => setData(item))
        
        return () => {
            setItem(null)
        }
    }, [])

    return item? (
        <div>
            {item.id}
            {item.producer}
        </div>
    ):(
        <div>
            ...
        </div> 
    )

}