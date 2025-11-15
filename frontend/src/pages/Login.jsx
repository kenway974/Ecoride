import LoginForm from '../components/forms/LoginForm';
import axiosClient from '../AxiosClient';
import { useNavigate } from 'react-router-dom';

export default function Login() {
  const navigate = useNavigate();

  const handleLogin = async (email, password) => {
    try {
      const { data } = await axiosClient.post('/login_check', { email, password });
      localStorage.setItem('token', data.token);
      navigate('/dashboard');
    } catch (err) {
      console.error(err.response?.data || err.message);
      alert('Login failed');
    }
  };

  return <LoginForm onSubmit={handleLogin} />;
}
