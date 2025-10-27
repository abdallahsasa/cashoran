import DashboardLayout from '@/layouts/dashboard/layout';
import { SharedData } from '@/types/global';
import { usePage } from '@inertiajs/react';
import { ReactNode } from 'react';
import Google from './partials/google';

interface Props extends SharedData {
   auths: Settings<GoogleAuthFields>[];
}

const Auth = ({ auths }: Props) => {
   const { props } = usePage<SharedData>();
   const { translate } = props;
   const { common } = translate;
   const components = [Google];

   const tabs = auths.map((auth, index) => ({
      ...auth,
      Component: components[index] ?? <div>{common.no_results_found}</div>,
   }));

   return (
      <section className="md:px-3">
         {tabs.map((auth) => (
            <auth.Component key={auth.id} auth={auth} />
         ))}
      </section>
   );
};

Auth.layout = (page: ReactNode) => <DashboardLayout children={page} />;

export default Auth;
