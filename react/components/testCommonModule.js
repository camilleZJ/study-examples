const commonM = require("./commonJsMoudle");

commonM.age = 26;
commonM.obj.a = 4;

export const Test = () => {
  return (
    <div>
      <p>{commonM.age}</p>
      <p>{commonM.obj.a}</p>
    </div>
  );
};
