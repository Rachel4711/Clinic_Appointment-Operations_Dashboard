import {createContext,useContext,useEffect,useState} from 'react';
import {api} from '../api/client';
const AuthContext=createContext(null);
export function AuthProvider({children}){const [user,setUser]=useState(null);const [loading,setLoading]=useState(true);useEffect(()=>{api('/user').then(r=>setUser(r.data)).catch(()=>setUser(null)).finally(()=>setLoading(false))},[]);const login=async(values)=>{const r=await api('/login',{method:'POST',body:values});setUser(r.data);return r};const logout=async()=>{await api('/logout',{method:'POST'});setUser(null)};return <AuthContext.Provider value={{user,loading,login,logout}}>{children}</AuthContext.Provider>}
export const useAuth=()=>useContext(AuthContext);
