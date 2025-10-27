import { SwitchProps } from '@radix-ui/react-switch';
import { Switch as SwitchContainer } from './ui/switch';

const Switch = ({ children, ...props }: SwitchProps) => {
   return <SwitchContainer dir="ltr" {...props} />;
};

export default Switch;
