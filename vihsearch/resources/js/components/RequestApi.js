//import Cookies from 'js-cookie';
import axios from "axios"
//import {server_uri} from "../env"
import {get,set} from "./cache"

export default function RequestApi(url,body, req, success, failer,responseType="") {
    req()
    let toCache = false
    if(body && body.table && body.filter)
    {
        const fromCache = get(body.table+JSON.stringify(body.filter))
        if(fromCache)
        {
            console.log("from cache",body.table+JSON.stringify(body.filter))
            console.log("REQ Axios")
            success(fromCache)
            return 
        }
        toCache = true
    }

    //const token = {token: Cookies.get('session')}
    let options = { headers: {},responseType:responseType }
    const server_uri = "http://localhost:8000"
    axios.post(server_uri+url , body, options).then(result =>{
        //console.log("REQ Axios",body,result)
        if(toCache)
        {
            console.log("to cache",body.table+JSON.stringify(body.filter))
            set(body.table+JSON.stringify(body.filter),result)
        }
        
        success(result)
        
    }).catch(err => {
        console.log(err)
        failer(err.message)
    })
}
