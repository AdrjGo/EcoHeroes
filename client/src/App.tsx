import { Outlet } from "react-router-dom";
import Layout from "./components/layout/Layout";

type AppProps = {
  title?: string;
};

function App({ title }: AppProps) {
  return (
    <div className="min-h-screen w-screen overflow-x-hidden bg-gray-100">
      <Layout title={title}>
        <main className="flex-1 w-full">
          <Outlet />
        </main>
      </Layout>
    </div>
  );
}

export default App;