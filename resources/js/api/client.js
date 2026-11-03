const API_URL = import.meta.env.VITE_API_URL || '';
let csrfReady = false;
async function csrf(){ if(!csrfReady){ await fetch(`${API_URL}/sanctum/csrf-cookie`,{credentials:'include'}); csrfReady=true; } }
function cookie(name){ return decodeURIComponent(document.cookie.split('; ').find(v=>v.startsWith(`${name}=`))?.split('=').slice(1).join('=') || ''); }
export async function api(path,{method='GET',body,headers={}}={}){
  if(!['GET','HEAD'].includes(method)){ await csrf(); }
  const options={method,credentials:'include',headers:{Accept:'application/json','Content-Type':'application/json','X-XSRF-TOKEN':cookie('XSRF-TOKEN'),...headers}};
  if(body!==undefined&&!['GET','HEAD'].includes(method))options.body=JSON.stringify(body);
  const response=await fetch(`${API_URL}/api${path}`,options);
  if(response.status===204)return null;
  const data=await response.json().catch(()=>({message:'Unexpected server response.'}));
  if(!response.ok){const error=new Error(data.message||'Request failed.');error.status=response.status;error.errors=data.errors||{};throw error;}
  return data;
}
export const query=(params={})=>{const q=new URLSearchParams();Object.entries(params).forEach(([k,v])=>{if(v!==''&&v!=null)q.set(k,v)});return q.toString()?`?${q}`:''};
