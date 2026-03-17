import { useSearchParams } from "react-router-dom";
import PublicLayout from "@/components/PublicLayout";
import PackageConfigurator from "@/components/home/PackageConfigurator";

export default function PreRegistration() {
  const [searchParams] = useSearchParams();
  const program = searchParams.get("program");

  return (
    <PublicLayout>
      <PackageConfigurator initialProgram={program} />
    </PublicLayout>
  );
}
