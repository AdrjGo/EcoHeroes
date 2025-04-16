type RecolectorCardProps = {
  photo?: string;
  name: string;
  ci: string;
  zoneRecolector: string;
  credentialNumber: string;
  rate: number;
};

export default function RecolectorCard({
  photo,
  name,
  ci,
  zoneRecolector,
  credentialNumber,
  rate,
}: RecolectorCardProps) {
  return (
    <div className="bg-[#0c6a11] rounded-lg shadow-lg sm:p-3 p-5 sm:w-56 w-10/12 flex flex-col items-center relative">
      <div className="rounded-full bg-slate-100 size-24  grid place-content-center">
        <picture>
          <img
            src={photo || "/image/perfil-blanco.webp"}
            alt="Recolector"
            className="rounded-full"
            onError={(e) => {
              (e.target as HTMLImageElement).src = "/image/perfil-blanco.webp";
              (e.target as HTMLImageElement).onerror = null;
            }}
          />
        </picture>
      </div>
      <h2 className="sm:text-lg text-base font-bold mt-4 text-white">
        {name ? name : "Nombre del Recolector"}
      </h2>
      <p className="mt-2 text-xs text-white">CI: {ci ? ci : "CI del Recolector"}</p>
      <p className="mt-2 text-xs text-white">
        {zoneRecolector ? zoneRecolector : "Zona del recolector."}
      </p>
      <p className="mt-2 text-xs text-white">Credencial: {credentialNumber}</p>
      <div className="bg-black rounded-2xl px-2 py-1 my-2 text-white">
        ⭐ {rate ? rate : 0}
      </div>
    </div>
  );
}
