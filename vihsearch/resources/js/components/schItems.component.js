import React, {useState, useEffect, useRef} from "react";
import RequestApi from "./RequestApi"
import InfiniteScroll from "react-infinite-scroll-component";

export default  function ScItems({table,childComponent:Component,childOptions, pageSize = 30}) 
{
    const [items, setItems] = useState([])
    const [hasMore, setHasMore] = useState(true)
    const [pageNum, setPageNum] = useState(0)

    function getData(table,page_size,page_num,options={})
    {
        RequestApi( "/api/getData",
                    { 'table' : table , "page_size":page_size , "page_num" :page_num, options:options},
                    () =>       {},
                    result =>   GetDataSuccess(result),
                    error =>    console.log("Error",error)
                    
                 )
    }

    function GetDataSuccess(result)
    { 
      const arr = Object.values(result.data)      
      if(arr.length < pageSize) setHasMore(false)
      setItems(items.concat(arr))
    }

    useEffect(()=>{
        getData(table, pageSize,pageNum,childOptions)
        setPageNum(pageNum+1)
    },[])


    function fetchMoreData() {
        
        getData(table, pageSize,pageNum,childOptions)
        setPageNum(pageNum+1)
        
    }

    return  (
        <InfiniteScroll
          dataLength={items.length}
          next={fetchMoreData}
          hasMore={hasMore}
          loader={<h4>Loading...</h4>}
          endMessage={
            <p style={{ textAlign: "center" }}>
              <b>-------</b>
            </p>
          }
        >
          
          {items.map((item) => (
            <div key={item.id}>
              <Component itemID={item.id} />
            </div>
          ))}
        </InfiniteScroll>
    )
  
}