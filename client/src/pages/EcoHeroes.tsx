import RecolectorCard from "../components/RecolectorCard";
import { temporalRecolectores } from "../types/temporal-recolectores";

export default function EcoHeroes() {
  return (
    <section className=" w-[90%] mx-auto my-5">
      <h2 className=" text-4xl font-bold text-black my-5">Nuestros Eco Heroes</h2>
      <div className="flex flex-wrap justify-center gap-4">
        {temporalRecolectores.map((recolector) => (
          <RecolectorCard
            rate={recolector.rate}
            name={recolector.name}
            ci={recolector.ci}
            zoneRecolector={recolector.zoneRecolector}
            photo={recolector.photo}
            credentialNumber={recolector.credentialNumber}
          />
        ))}
      </div>
    </section>
  );
}
