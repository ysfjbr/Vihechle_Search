import React, {useState, useEffect} from "react"
import ReactDOM from 'react-dom';
import ScItems from "./schItems.component"



function App() {
    const [descSort, setDescSort] = useState(-1)

    useEffect(()=> {
        document.title = "Search"
        //getData()  childOptions={}
    },[])

    return (
        <div className="container">
            <div>
                <input  type="radio" className="hidden"
                        name="radioGroup" 
                        tabIndex="0" value="-1"  
                        onChange={()=> setDescSort(-1)} />
                Desc
                </div>
                <div>
                <input  type="radio" className="hidden"
                        name="radioGroup"
                        tabIndex="1" value="1" 
                        onChange={()=> setDescSort(1)} />
                Asc
            </div>

            <ScItems key={descSort} table="vihechle" childComponent = {SearchItem} />
        </div>
    );
}

export default App;

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
