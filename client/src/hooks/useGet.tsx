import { useState, useEffect } from "react";

type UseGetResult<T> = {
  data: T | null;
  lastPage?: number;
};

function useGet<T>(url: string): UseGetResult<T> {
  const [data, setData] = useState<T | null>(null);
  const [lastPage, setLastPage] = useState<number>(1);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const response = await fetch("https://backendecoherores-production.up.railway.app/api/" + url);
        if (!response.ok) {
          throw new Error(`HTTP error ${response.status}`);
        }
        const result = await response.json();
        console.log(result.data.data);
        setData(result.data.data || null);
        console.log(result.data.last_page);
        setLastPage(result.data.last_page);
      } catch (error) {
        console.log("HUBO UN ERROR", error);
      }
    };

    fetchData();
  }, [url]);

  return { data, lastPage };
}

export default useGet; 
